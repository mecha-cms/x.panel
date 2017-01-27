GUI Extension for Mecha
=======================

Start
-----

 - Upload the `panel` folder to `lot\extend` folder.
 - Create a page file in `engine\lot\user` as your user data:

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
 - Users

### Plugin

 - Markdown Parser