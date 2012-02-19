<?php

/**
 * @file
 * Simple, extensible user import from a CSV file.
 */

/**
 * User import multi-part form.
 */
function uif_import_form($form, &$form_state) {
  // Cause return to beginning if we just completed an import
  if (isset($form_state['storage']['step']) && $form_state['storage']['step'] >= 3) {
    unset($form_state['storage']);    
  }
  
  $step = empty($form_state['storage']['step']) ? 1 : $form_state['storage']['step'];
  $form_state['storage']['step'] = $step;

  switch ($step) {
    case 1:
      $form['instructions'] = array(
        '#type' => 'fieldset',
        '#title' => t('User import help'),
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
      );
      $form['instructions']['help'] = array(
        '#markup' => theme('uif_form_help'),
      );
      $file_size_msg = t('Your PHP settings limit the maximum file size per upload to %size. Depending on your server environment, these settings may be changed in the system-wide php.ini file, a php.ini file in your Drupal root directory, in your Drupal site\'s settings.php file, or in the .htaccess file in your Drupal root directory.', array('%size' => format_size(file_upload_max_size())));

      $form['user_upload'] = array(
        '#type' => 'file',
        '#title' => t('Import file'),
        '#size' => 40,
        '#description' => t('Select the CSV file to be imported.') . '<br />' . $file_size_msg,
      );
  
      $preview_count = drupal_map_assoc(array(0, 1, 10, 100, 1000, 10000, 9999999));
      $preview_count[0] = t('None - just do it');
      $preview_count[9999999] = t('Preview all');

      $form['preview_count'] = array(
        '#type'          => 'select',
        '#title'         => t('Users to preview'),
        '#default_value' => 10,
        '#options'       => $preview_count,
        '#description'   => t('Number of users to preview before importing. Note: If you run out of memory set this lower or increase your memory.')
      );

      $form['notify'] = array(
       '#type' => 'checkbox',
       '#title' => t('Notify new users of account'),
       '#description'   => t('If checked, each newly created user will receive the <em>Welcome, new user created by administrator</em> email using the template on the <a href="@url1">user settings page</a>. This is the same email sent for <a href="@url2">admin-created accounts</a>.', array('@url1' => url('admin/user/settings'), '@url2' => url('admin/user/user/create'))),
      );

      $form['next'] = array(
        '#type' => 'submit',
        '#value' => t('Next')
      );    
  
      // Set form parameters so we can accept file uploads.
      $form['#attributes'] = array('enctype' => 'multipart/form-data');
      break;
      
    case 2:
      $form['instructions'] = array(
        '#markup' => t('Preview these records and when ready to import click Import users.'),
        '#prefix' => '<div id="uif_form_instructions">',
        '#suffix' => '</div>',
      );
      $form['user_preview'] = array(
        '#markup' => $form_state['storage']['user_preview'],
        '#prefix' => '<div id="uif_user_preview">',
        '#suffix' => '</div>',
      );
      $form['back'] = array(
        '#type' => 'submit',
        '#value' => t('Back'),
        '#submit' => array('uif_import_form_back'),
      );
      $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Import users'),
      );
      break;
  }

  return $form;    
}

/**
 * Validate the import data.
 */
function uif_import_form_validate($form, &$form_state) {
  $step = empty($form_state['storage']['step']) ? 1 : $form_state['storage']['step'];
  
  switch ($step) {
    case 1:      
      // Validate the upload file
      $validators = array(
        'file_validate_extensions' => array('csv'),
        'file_validate_size' => array(file_upload_max_size()),
      );

      if ($user_file = file_save_upload('user_upload', $validators)) {
        $errors = uif_validate_user_file($user_file->uri, $data);
        if (!empty($errors)) {
          form_set_error('user_upload', '<ul><li>' . implode('</li><li>', $errors) . '</li></ul>');
          return;
        }
      }
      else {
        form_set_error('user_upload', t('Cannot save the import file to temporary storage.  Please try again.'));
        return;
      }

      // Save the validated data to avoid reparsing
      $form_state['storage']['data'] = $data;
      break;
  }
}

/**
 * Form submission handler.
 */
function uif_import_form_submit($form, &$form_state) {
  $step = empty($form_state['storage']['step']) ? 1 : $form_state['storage']['step'];
  
  if (1 == $step) {
    $form_state['rebuild'] = TRUE;
    $form_state['storage']['notify'] = isset($form_state['values']['notify']) ? $form_state['values']['notify'] : FALSE;
    $preview_count = $form_state['values']['preview_count'];
    if ($preview_count) {
      $form_state['storage']['preview_count'] = $preview_count;
      $form_state['storage']['user_preview'] = theme('uif_preview_users', array('data' => $form_state['storage']['data'], 'limit' => $preview_count));
    }
    else {
      $step = 2;
    }
  }

  if (2 == $step) {
    $form_state['rebuild'] = TRUE;
    uif_batch_import_users($form_state);
  }
  
  $form_state['storage']['step'] = $step + 1;
}

/**
 * Read the user import file and validate on the way.
 *
 *  @param $uri
 *    filepath to the user import file
 *  @param $data
 *    returns with array of users
 *  @return
 *    FALSE if no errors found
 *    array of error strings if error found
 */
function uif_validate_user_file($uri, &$data) {
  $data = array();
  $data['user'] = array();
  $line = 0;

  // Without this fgetcsv() fails for Mac-created files
  ini_set('auto_detect_line_endings', TRUE);  
  
  if ($fp = fopen($uri, 'r')) {
    // Read the header
    $header_row = uif_normalize_header(array_map('trim', fgetcsv($fp)));
    $line++;
    $errors = module_invoke_all('uif_validate_header', $header_row);
    uif_add_line_number($errors, $line);
    if (!empty($errors)) {
      return $errors;
    }
    $data['header'] = $header_row;
    
    // Read the data
    $errors = array();
    while (!feof($fp) && (count($errors) < 20)) {
      $row = fgetcsv($fp);
      $line++;
      if (uif_row_has_data($row)) {
        $user_row = array_combine($header_row, array_map('trim', $row));
        $uid = db_query_range('SELECT uid FROM {users} WHERE mail = :mail', 0, 1, array(':mail' => $user_row['email']))->fetchField();
        
        $more_errors = module_invoke_all('uif_validate_user', $user_row, $uid, $header_row);
        uif_add_line_number($more_errors, $line);
        $errors = array_merge($errors, $more_errors);
        $data['user'][] = $user_row;
      }
    }
    
    // Any errors?
    if (!empty($errors)) {
      return $errors;
    }
  }
  else {
    return t('Cannot open that import file.');
  }
}

/**
 * Is there data in the row?
 */
function uif_row_has_data($row) {
  if (isset($row) && is_array($row)) {
    foreach ($row as $value) {
      $value = trim($value);
      if (!empty($value)) {
        return TRUE;
      }
    }
  }
  return FALSE;
}

/**
 * Normalize the header columns.
 */
function uif_normalize_header($header) {
  $normal_header = array();
  foreach ($header as $column) {
    $normal_header[] = strtolower($column);
  }
  return $normal_header;
}

/**
 * Implementation of hook_uif_validate_header().
 */
function uif_uif_validate_header($header) {
  foreach ($header as $column) {
    if ('email' === $column) {
      $email_found = TRUE;
    }
  }
  
  if (!$email_found) {
    return t('I can find no email column in the import file.');    
  }
}

/**
 * Implementation of hook_uif_validate_user().
 */
function uif_uif_validate_user($user_data, $uid, $header = NULL) {
  if (!valid_email_address($user_data['email'])) {
    return t('Missing or invalid email address !mail.', array('!mail' => $user_data['email']));
  }
}

/**
 * Prepend the line number on the error.
 */
function uif_add_line_number(&$errors, $line) {
  foreach ($errors as &$error) {
    $error = t('Error on row !line: ', array('!line' => $line)) . $error;
  }
}

/**
 * Return user to starting point on template multi-form.
 */
function uif_import_form_back($form, &$form_state) {
  $form_state['storage']['step'] = 1;
}

/**
 * Theme preview of all users.
 */
function theme_uif_preview_users($variables) {
  $data = $variables['data'];
  $limit = $variables['limit'];
  $current = 0;
  $output = '';
  
  foreach ($data['user'] as $user_data) {
    $current++;
    if ($current > $limit) {
      break;
    }
    $output .= theme('uif_preview_one_user', array('data' => $user_data));
  }
  
  if (!$output) {
    $output = t('There are no users to import.');
  }
  
  return $output;
}

/**
 * Theme preview of a single user.
 */
function theme_uif_preview_one_user($variables) {
  $user_data = $variables['data'];
  $rows = array();
  foreach ($user_data as $field => $value) {
    $rows[] = array($field, $value);
  }
  
  $user_exists = db_query('SELECT COUNT(*) FROM {users} WHERE mail = :mail', array(':mail' => $user_data['email']))->fetchField();
  $annotation = $user_exists ? t('update') : t('create');
  $heading = $user_data['email'] . ' (' . $annotation . ')';
  
  return '<h3>' . $heading . '</h3>' . theme('table', array('rows' => $rows));
}

/**
 * Batch import all users.
 */
function uif_batch_import_users($form_state) {
  $batch = array(
    'title' => t('Importing users'),
    'operations' => array(
      array('uif_batch_import_users_process', array($form_state))
    ),
    'progress_message' => '', // uses count(operations) which is irrelevant in this case
    'finished' => 'uif_batch_import_users_finished',
    'file' => drupal_get_path('module', 'uif') . '/uif.admin.inc',
  );
  batch_set($batch);
}

/**
 * User import batch processing.
 */
function uif_batch_import_users_process($form_state, &$context) {
  // Initialize
  if (empty($context['sandbox']['progress'])) {
    $context['sandbox']['progress'] = 0;
    $context['sandbox']['max'] = count($form_state['storage']['data']['user']);
    $context['results']['created'] = 0;
    $context['results']['updated'] = 0;
  }
  
  // Process max 20 users at a time
  $processed = 0;
  $notify = $form_state['storage']['notify'];
  while ($context['sandbox']['progress'] < $context['sandbox']['max'] && $processed < 20) {
    $index = $context['sandbox']['progress'];
    uif_import_user($form_state['storage']['data']['user'][$index], $notify, $context['results']);
    $context['sandbox']['progress']++;
    $processed++;
  }
  
  // Finished yet?
  if ($context['sandbox']['progress'] != $context['sandbox']['max']) {
    $context['finished'] = $context['sandbox']['progress'] / $context['sandbox']['max'];
  }
}

/**
 * User import batch completion.
 */
function uif_batch_import_users_finished($success, $results, $operations) {
  if ($success) {
    global $user;
    if (isset($results['self'])) {
      uif_update_user($results['self'], $user->uid);
      $results['updated']++;
      unset($results['self']);
    }      
    $done = t('User import complete. ');
    $created = $results['created'] ? format_plural($results['created'], 'One user was created.', '@count users were created.') . ' ' : '';
    $updated = $results['updated'] ? format_plural($results['updated'], 'One user was updated.', '@count users were updated.') . ' ' : '';
    $more = t('View the <a href="@url">user list</a>.', array('@url' => url('admin/people')));
    drupal_set_message($done . $created . $updated . $more);
  }
  else {
    drupal_set_message(t('An error occurred and processing did not complete.'), 'error');
  }
}

/**
 * Import one user.
 */
function uif_import_user($user_data, $notify, &$results) {
  if ($uid = db_query('SELECT uid FROM {users} WHERE mail = :mail', array(':mail' => $user_data['email']))->fetchField()) {
    global $user;
    if ($uid === $user->uid) {
      $results['self'] = $user_data;
      return;
     }
    $account = uif_update_user($user_data, $uid);
    $results['updated']++;
  }
  else {
    $account = uif_create_user($user_data, $notify);
    $results['created']++;
  }
}

/**
 * Create a new user.
 */
function uif_create_user($user_data, $notify) {
  $account = array();
  $account['mail'] = $user_data['email'];
  $account['init'] = $user_data['email'];
  $account['status'] = 1;
  
  // Use the provided username if any, or derive it from the email
  $username = empty($user_data['username']) ? preg_replace('/@.*$/', '', $user_data['email']) : $user_data['username'];
  $account['name'] = uif_unique_username($username);
  
  // Use the provided password if any, otherwise a random one
  $pass = !empty($user_data['password']) ? $user_data['password'] : user_password();
  $account['pass'] = $pass;
  
  $account = array_merge($account, module_invoke_all('uif_pre_create', $account, $user_data));
  $account = user_save('', $account);
  module_invoke_all('uif_post_create', $account, $user_data);

  if ($notify) {
    $account->password = $pass;    // For mail token; _user_mail_notify() expects this
    _user_mail_notify('register_admin_created', $account);
  }

  return $account;
}

/**
 * Update an existing user.
 */
function uif_update_user($user_data, $uid) {
  $account = user_load($uid);
  
  // findme todo: Support update of user mail, name, and password
  // Supporting user mail change requires optional inclusion of uid column, which
  // would override use of email column as uid lookup method.
  $additions = module_invoke_all('uif_pre_update', $account, $user_data);
  $account = user_save($account, $additions);
  module_invoke_all('uif_post_update', $account, $user_data);
  return $account;
}

/**
 * Given a starting point for a Drupal username (e.g. the name portion of an email address) return
 * a legal, unique Drupal username.
 *
 * @param $name
 *   A name from which to base the final user name.  May contain illegal characters; these will be stripped.
 *
 * @param $uid
 *   (optional) Uid to ignore when searching for unique user (e.g. if we update the username after the 
 *   {users} row is inserted) 
 *
 * @return
 *   A unique user name based on $name.
 *
 */
function uif_unique_username($name, $uid = 0) {
  // Strip illegal characters
  $name = preg_replace('/[^\x{80}-\x{F7} a-zA-Z0-9@_.\'-]/', '', $name);
  
  // Trim whitespace
  $name = trim($name);
  
  // Convert any series of spaces to a single space
  $name = preg_replace('/ +/', ' ', $name);
  
  // If there's nothing left use a default
  $name = ('' === $name) ? t('user') : $name;
  
  // Truncate to reasonable size
  $name = (drupal_strlen($name) > (USERNAME_MAX_LENGTH - 10)) ? drupal_substr($name, 0, USERNAME_MAX_LENGTH - 11) : $name;
  
  // Iterate until we find a unique name
  $i = 0;
  do {
    $newname = empty($i) ? $name : $name . '_' . $i;
    $args = array(':uid' => $uid, ':name' => $newname);
    $found = db_query_range('SELECT uid from {users} WHERE uid <> :uid AND name = :name', 0, 1, $args)->fetchField();
    $i++;
  } while ($found);
  
  return $newname;
}

/**
 * Theme function for import form help.
 */
function theme_uif_form_help() {
  $basic_help = '<p>' . t('Choose an import file. You\'ll have a chance to preview the data before doing the import. The import file must have a header row with a name in each column for the value you are importing. The header names are not case sensitive. Importable fields include:') . '</p>';
  $items = array(
    t('email (required) - the user\'s email'),
    t('username (optional) - a name for the user. If not provided, a name is created based on the email.'),
    t('password (optional) - a password for the user. If not provided, a password is generated.')
  );
  $basic_help .= theme('item_list', array('items' => $items));
  
  $helps = module_invoke_all('uif_help');
  array_unshift($helps, $basic_help);
  $output = '';
  foreach ($helps as $help) {
    $output .= '<div class="uif_help_section">' . $help . '</div>';
  }
  
  return $output;
}

