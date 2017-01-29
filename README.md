GUI Extension for Mecha
=======================

Start
-----

 - Make sure you have uploaded the required extensions and plugins.
 - Upload the `panel` folder to `lot\extend` folder.
 - Create a page file in `engine\log\user` as your first user data:

   ~~~ .yaml
   ---
   author: Taufik Nurrohman
   link: http://mecha-cms.com
   status: 1
   ...

   I am the main author of this site.
   ~~~

   Save as `test-user.page`.
 - Now go to `http://localhost/panel/::g::/enter`.
 - In the **User** field, type `test-user`.
 - In the **Pass** field, type your brand new password.
 - You should be redirected to `http://localhost/panel/::g::/page`.
 - Done for now.

Dependency
----------

### Extension

 - Assets
 - Pages
 - [Tags](https://github.com/mecha-cms/extend.tag)
 - [Users](https://github.com/mecha-cms/extend.user)

### Plugin

 - Markdown Parser