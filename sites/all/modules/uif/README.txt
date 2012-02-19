
The User Import Framework (uif) module provides simple, extensible user import via CSV (comma-separated value) files. The guiding philosophy is to make the import process as simple as possible for the end-user (the user doing the import). Hooks allow the developer to add the bells and whistles they need, e.g. content_profile support, while providing a strong base of support. 

User import is a two-step process. 1) The user chooses a csv file, along with specifying the number of records they'd like to preview (this preview can be skipped by power users). The user can also choose whether new users get a welcome email. 2) If happy with the preview, user submits the form again and users are created (or modified, if the user already exists).


Alternatives
------------
This module is unrelated to User Import (http://drupal.org/project/user_import). User Import has a great deal of functionality built in, and endeavors to integrate with several other modules. It has a more involved user interface, which may be better suited for technical administrators. This module, on the other hand, includes only critical user interface elements, with the goal of optimizing the user experience for non-technical administrators. Added functionality is provided by you, the developer, and to that end don't expect too much in the way of feature extensions to this module (but try me anyway).


Installation
------------
1) Copy the uif directory to the modules folder in your installation.

2) Enable the module.

3) Enable 'import users' permission for any role(s) you want to be able to do imports.

4) Go to People > Import (/admin/people/uif) and import some users.


Basic Setup
-----------
Out of the box, this module does very basic user import. It can only import only users' email, username, and password. Of these three, only an email address is required. If no username is provided, the module creates a unique one based on the email. If no password is provided, a random one is generated. 

Only CSV file format is supported. The import file must have a header row, and it must contain a column labeled "email" (no quotes). If username and/or password is provided in any of the user data rows, there must be header columns labeled "username" or "password" respectively. Header case is ignored.


Extensibility
-------------
Several hooks are provided so that you can, for example, import more information about users, such as profile info. It is the developer's responsibility to write hook functions to do the work, e.g. create a profile node for content_profile module.

hook_uif_help()
This hook allows you to insert help text on the import page. The text appears beneath the basic help provided by the module.

hook_uif_validate_header($header)
Your module can implement this hook to validate the array of header values. You may require certain fields for a profile. This is the place to check if the column exists in the import file. Return an array of error strings, all of which will be displayed, and the user will not be allowed to do the import. An empty array (or NULL) is success.

hook_uif_validate_user($user_data, $uid)
This hook is called for every row of user data in the file. Like hook_uif_validate_header(), you can return any number of error strings, which are displayed to the user and which cause the import to fail. $user_data is an array of key => value pairs, where the key is the header column and the value is the data from the user row. All values are trimmed. $uid is either 0 for a new user or a positive integer for an existing user.

hook_uif_pre_create($account, $user_data)
This hook is called before a new user is created (before user_save() is called). $account contains basic account data (mail, name, pass). $user_data contains key => value pairs for the user about to be created.

hook_uif_post_create($account, $user_data)
This hook is called after a new user is created (after user_save() is called). Same arguments as hook_uif_pre_create(). Good place to do work, like creation of a profile node.

hook_uif_pre_update($account, $user_data)
Same idea as hook_uif_pre_create(), but used for existing (updated) users. In this case, $account->uid will be a positive integer.

hook_uif_post_update($account, $user_data)
Same idea as hook_uif_post_create(), but used for existing (updated) users. In this case, $account->uid will be a positive integer.

Of course, since user_save() is called, core's hook_user() is available to you as well.

