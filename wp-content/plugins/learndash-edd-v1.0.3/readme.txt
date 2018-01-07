=== LearnDash EDD Integration Plugin ===

== Changelog ==
= 1.0 =
Initial release

=1.0.1 =
* Updated for compatibility with EDD free download add-on

= 1.0.2 =
* Updates to function update_course_access() to perform EDD transaction lookup by user email instead of user ID. Basic issue is EDD transactions table is not unique by user ID. But is by user email. A query by user ID could return wrong row.

= 1.0.3 =
* Updated to to use EDD’s select field for courses on downloads
* Fixed intermittent 500 error that prevented successful purchase