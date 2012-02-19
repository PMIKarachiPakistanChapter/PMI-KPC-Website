<?php

/**
 * @file
 * Builds placeholder replacement tokens for user-related data.
 */

/**
 * Implements hook_token_info().
 */
function uid_example_token_info() {
  $types['user'] = array(
    'name' => t('Users'),
    'description' => t('Tokens related to individual user accounts.'),
    'needs-data' => 'user',
  );

  $user['field_firstname'] = array(
    'name' => t('First Name'),
    'description' => t("User first name."),
  );
  $user['field_lastname'] = array(
    'name' => t("Last Name"),
    'description' => t("User last name."),
  );

  return array(
    'types' => $types,
    'tokens' => array('user' => $user),
  );
}

/**
 * Implements hook_tokens().
 */
function uif_example_tokens($type, $tokens, array $data = array(), array $options = array()) {
  $url_options = array('absolute' => TRUE);
  if (isset($options['language'])) {
    $url_options['language'] = $options['language'];
    $language_code = $options['language']->language;
  }
  else {
    $language_code = NULL;
  }
  $sanitize = !empty($options['sanitize']);

  $replacements = array();

  if ($type == 'user' && !empty($data['user'])) {
    $account = $data['user'];
    foreach ($tokens as $name => $original) {
      switch ($name) {
        case 'field_firstname':
          $name = $account->field_firstname;
          $name = $name[LANGUAGE_NONE][0]['value'];
          $replacements[$original] = $sanitize ? check_plain($name) : $name;
          break;
      
        case 'field_lastname':
          $name = $account->field_lastname;
          $name = $name[LANGUAGE_NONE][0]['value'];
          $replacements[$original] = $sanitize ? check_plain($name) : $name;
          break;

      }
    }
  }

  return $replacements;
}
