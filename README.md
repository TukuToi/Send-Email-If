# Send-Email-If
Sends an Email if specific conditions are met to specific receivers. Currently raw code must be edited to amend conditions or receivers.

The Plugin sends an email to the user of which the username corresponds to the post/page visited. The mail will reveal to the receiver who visited the post, if possible.


# Usage

- Install and activate like any other WordPress plugin.
- Create a new post or page with Post Title precisely equal to the `username` (all in lower case) of any existing WordPress user.
- View that page or post as any user or guest.
- The user with the same username as post name you visited will receive an email telling him/her you visited the specific post

This all sounds very raw and useless but it can be useful to for example notify someone when someone else viewes, downloads or else interacts with some content somehow related to an(y) user.

Since the plugin is based on the actual query the user runs, almost anything can be monitered.

The plugin uses WordPreess core `wp_mail()` so it should work out of the box in any WordPress ennvironment without any need for additional email handlers. 

