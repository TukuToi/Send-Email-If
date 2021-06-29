=== TukuToi Send Email If ===
Contributors: bedas
Donate link: https://www.tukutoi.com/
Tags: email, visitor, view, notification, conditional, talent
Requires at least: 4.9
Tested up to: 5.7
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

TukuToi Send Email If Plugin allows you to send an email to a dynamically set receiver, whenever any Post of a dynamically set type is visited by users of predefined roles.

== Description ==

Sometimes, you need to know when certain posts are being viewed/visited/discovered by users, be it guests or logged in users.
For example, in a Talent Discovery Directory you might want to inform the Talent or Talent Manager that someone is vieweing their Talent Profile right now. You might want to do that discretly, but reliably, without bloating the website with heavy (and Privacy problematic) user tracking. 

This plugin has a solution to that problem.
It allows you to easily send a receiver. You can set a specific receiver for each post. 
You can control on what posts the receiver should be set, and thus the email sent when the post is visited.
You can control what user Roles will trigger the notification when they visit the post.
You can also customize the email contents as well as other aspects of the email.

The entire plugin is very lightweight and thus renounces to any Backend Settings Screens. 
It only adds a tiny Metabox to your posts (of choice) to save the receiver email.
The rest of the Plugin functionality is controlled with a set of Filters.

== Installation ==

1. Install and Activate like any other Plugin in WordPress.
1. Head to the Plugin Settings Screen in the WordPress Dashboard > TukuToi > Send Email If. There you can control the Plugin settings such as determine which posts should trigger an email, and what action should send the email, and customize the email sent.
1. Control the plugin settings with the provided Filters (see FAQ)
1. Save the email for each specific post where you want an email being sent.

== Frequently Asked Questions ==

= This Plugin has no settings! =

That's right, in order to keep this lightweight, no settings or options pages where added in the backend.
Instead, a set of Filters will allow you to control the Plugin behaviour fully.
Read more about this in the below FAQ entries.

= How to modify the email "About"? =

You can use the filter `tkt_sei_email_subject` in order to customize the "About" of the Email sent.
You just need to return a valid Email "About" (string) in the callback function:

<pre><code>
add_filter('tkt_sei_email_subject', 'my_custom_email_subject');
function my_custom_email_subject(){

	$subject ='My Custom About';

	return $subject;

}
</code></pre>

= How to modify the email "Body"? =

You can use the filter `tkt_sei_notification_content` in order to customize the "Body" of the Email sent.
You just need to return a valid Email "Body" (string or html with inline CSS) in the callback function:
The Filter passes 3 additional arguments for your convenience.

<pre><code>
add_filter('tkt_sei_notification_content', 'my_custom_notification_content', 10, 4);
function my_custom_notification_content($default_message, $post, $current_user, $receiver_email){
	
	//$post is the Post Object of the post which was visited.
	//$current_user is the User Object of the user who visited the post. Might be empty, if guest user.
	//$receiver_email is the email stored to the post visited (the email that will get the notification).

	$default_message = 'My new Custom Notification Content with <strong>valid HTML</strong>';

	return $default_message;

}
</code></pre>

= How to modify the email "To" (Receiver)? =

You can use the filter `tkt_sei_receiver_email` in order to customize the "To" (Receiver) of the Email sent.
You just need to return a valid Email address in the callback function:

<pre><code>
add_filter('tkt_sei_receiver_email', 'my_custom_receiver_email');
function my_custom_receiver_email(){

	$email ='custom@receiver.com';

	return $email;

}
</code></pre>

= How can I control where the Metabox appears, and which posts should be observed/triggering the email? =

You can use the filter `tkt_sei_metabox_location` in order to customize the "Location" where the metabox will appear.
In other words, with this filter you can decide on what Post Types you will want the plugin functionality active.
You just need to return an array of valid Post Types in the callback function:

<pre><code>
add_filter('tkt_sei_metabox_location', 'my_custom_metabox_location');
function my_custom_metabox_location(){

	$posts = array('post', 'page');//Array of post Types where the functionality shoud be active.

	return $posts;

}
</code></pre>

= How can  I control what users (visitors) trigger the email? =

You can use the filter `tkt_sei_notify_on_role_visit` in order to customize the "Visitor" Role that should trigger the email.
In other words, this allows you to determine what User Roles will actually trigger the emails, when an user of such role visits a post which is observed.
The filter expects an array of valid User Roles.
Pass 0 (int) if you want guests to trigger emails.
Empty array (default) triggers no email at all.

<pre><code>
add_filter('tkt_sei_notify_on_role_visit', 'my_custom_notify_on_role_visit');
function my_custom_notify_on_role_visit(){

	$role = array( 'administrator', 0, 'subscriber' );// it sends the email if a user with role administrator, or subscriber, or even a guest is visiting.

	return $role;

}
</code></pre>

== Screenshots ==

1. The Email MetaBox in the Post Edit Screen.
2. The Email as received (raw text, HTML will be expanded in real live case). 

== Changelog ==

= 1.0 =
* Initial Commit.