=== Fluent Support Pro - WordPress Helpdesk and Customer Support Ticket Plugin ===
Contributors: techjewel, wpmanageninja, adreastrian
Tags: support, ticketing, fluent support
Requires at least: 5.6
Tested up to: 6.8
Stable tag: 1.8.9
Requires PHP: 7.3

Fluent Support Pro Version

== Installation ==
if you already have Fluent Support plugin then You have to install this plugin to get the additional features.

Installation Steps:
-----
1. Goto  Plugins » Add New
2. Then click on "Upload Plugins"
3. Then Click "Choose File" and then select the fluent-support-pro.zip file
4. Then Click, Install Now after that activate that.
5. You may need to activate the License, and You will get the license at your WPManageninja.com account.

Manual Install:
------------------------
Upload the plugin files to the /wp-content/plugins/ directory, then activate the plugin.

== Changelog ==

= 1.8.9 (Date: June 4, 2025) =
* Added: Integration with Fluent Community.
* Added: Informational replies on admin ticket responses.
* Improved: Internal note functionality now works for closed tickets.
* Improved: Responses in closed tickets can now be edited.
* Fixed: Error message shown for the Priority field when the Product is required in the customer portal.
* Fixed: Fatal error triggered by the Share Essentials weekly cron job.
* Fixed: Customer responses not displaying in agent response stats within agent reports.
* Fixed: Incorrect ticket response count shown in today's stats.
* Fixed: Broken media upload CSS in the page editor caused by the [fluent_support_admin_portal] shortcode.
* Fixed: "Waiting For Reply" filter not working in advanced filtering.
* Fixed: Responsive issues in the Agent Portal.

= 1.8.8 (Date: Apr 22, 2025) =
* Fixed: Ticket status was not displaying correctly on the ticket view page in the customer portal.
* Fixed: File types, quantity limits, and file sizes were not displaying correctly in the customer portal.
* Fixed: Missing translations in the customer portal.
* Fixed: Changes to ticket form configurations were not reflecting in the customer portal.
* Fixed: Removed unnecessary API call on the customer portal page.
* Fixed: Custom field not updating correctly via REST API.
* Fixed: Issue with Fluent Form and Fluent CRM integration.
* Fixed: Issues with updating actions in the workflow.
* Fixed: Issue with conflicting forms in the customer portal's registration and password reset process.

= 1.8.7 (Date: Mar 20, 2025) =
* Improvement: Enhanced and refined the Customer Portal UI/UX.
* Improvement: New block editor for the updated Customer Portal layout.
* Improvement: Resume options for incomplete Freshdesk ticket migrations.
* Fixed: Images pasted directly are deleted after some time.
* Fixed: Unable to edit customers with no name in the customer list.
* Fixed: Attachment issue when creating a ticket from Fluent Forms.
* Fixed: Issue with unchecking agent permissions.
* Fixed: Missing option to delete custom fields during creation.
* Fixed: Creating an action in a workflow also creates a duplicate action.
* Fixed: Advanced filter does not work when a multi-select option is selected.
* Fixed: Unable to assign users to a new business box if the current one is restricted to agents.
* Fixed: Product not displaying in WooCommerce widget.
* Fixed: Conditional logic for custom fields not working properly when creating a ticket from the admin portal.
* Fixed: Direct copy-paste of images was not functioning properly in multisite.

= 1.8.6 (Date: Feb 28, 2025) =
* Fixed: Textdomain Consistency: Standardized all translation string textdomains across the plugin for better internationalization support.
* Fixed: Enhanced Security: Implemented proper data escaping and improve overall plugin security.
* Fixed: Media Protection: Reinforced security measures for attachment uploads by restricting direct access to image directories and implementing proper file path validation.

= 1.8.5 (Date: Dec 26, 2024) =
* Added: Agent Time Tracking (Pro)
* Added: Saved search (Pro)
* Added: Keyboard Shortcuts
* Fixed: Issue where the Customer Portal's rich text editor reverted to a basic editor upon reload.
* Fixed: Recaptcha functionality issue in the Customer Portal.
* Fixed: WorkFlow action sequence title issue.
* Fixed: Issue where embedded content appeared in preformatted form in the reply message after reloading.
* Fixed: Compatibility issue with the Sequential Order Plugin.
* Fixed: 404 error page not showing when an incorrect ticket number is entered in the ticket view URL.
* Fixed: Error message displaying when the Customer Portal loads.
* Fixed: Notes within a closed ticket becoming non-editable.
* Fixed: Restricted agents being incorrectly assigned to a mailbox through the ticket dashboard's bulk action feature.
* Fixed: Email verification message translation and verification message hooks not functioning properly.

= 1.8.2 (Date: Sep 25, 2024) =
* Added: Workflow action sequence (Pro)
* Added: Direct paste image in WP Editor
* Fixed: Required sign not visible for product options marked as required during ticket creation
* Fixed: Reply button toggle option not working in the admin portal
* Fixed: Issue with the route URL on the All Tickets page
* Fixed: Filter not refreshing in the Customer Portal after clicking the (❌) icon in the search field
* Fixed: OpenAI routing issue resolved
* Fixed: CSS issue related to ticket filter theme in the Customer Portal
* Fixed: Responsive issue on the view ticket page
* Fixed: After importing tickets from another SaaS platform, the agent is imported but not synchronized with the system

= 1.8.1 (Date: Sep 10, 2024) =
* Fixed - Email verification vulnerability issue in signup
* Fixed - Customer portal ticket fetching vulnerability issue

= 1.8.0 (Date: Aug 15, 2024) =
* Added - Integration with OpenAI (Pro)
* Added - Product Filter and sorting option in customer portal
* Added - Email verification in sighup for customer
* Added - Two-FA in signing for customer
* Fixed - Freshdesk ticket migration issue
* Fixed - Response message not being saved correctly in the auto-close settings
* Fixed - Data remaining in the "Create Customer" form after creating a customer in the Customers module
* Fixed - Form not resetting after creating a new ticket on behalf of a customer from the dashboard by support staff
* Fixed - Shortcode always being pasted below the content
* Fixed - Tickets still being deletable even when the "Delete Ticket" permission is unchecked
* Fixed - Workflow issue when the ticket-closing condition is triggered

= 1.7.90 (Date: May 28, 2024) =
* Added - Activity Trends by Time of Day (Pro)
* Added - Integration with Fluent Boards (Pro)
* Added - Integrations Logs
* Added - Upload ticket attachments to their respective ticket folders in Google Drive, organized accordingly (Pro)
* Added - Duplicate or clone workflows (Pro)
* Added - Required option  in product field (Pro)
* Fixed - If the site language is not set to English, the workflow always defaults to manual mode
* Fixed - Inbox identifier css issue in all tickets table
* Fixed - If anyone choose View dashboard and draft_reply then it will not show any tickets
* Fixed - Freshdesk ticket migration issue
* Fixed - Zendesk ticket migration issue
* Fixed - Clicking the "Import Tickets" button in the ticket migration module opens multiple modals simultaneously
* Fixed - Issue with Bookmark
* Fixed - When the file name is too long, the file will not upload during ticket creation or in responses
* Fixed - If a restriction is applied to a specific business box, it still appears on the dashboard
* Fixed - MemberPress integration to show separate lists for recurring and non-recurring subscriptions
* Fixed - The WooCommerce widget is not shown on the 'View Customer' page

= 1.7.80 (Date: April 3, 2024) =
* Added - Restrict business boxes for specific agents
* Added - Ticket search feature in customer portal
* Added - MemberPress Integration
* Added - Option to resume the migration process for the last incomplete ticket in Helpscout (Pro)
* Added - Display the exact time of ticket or response creation upon hovering over it in the admin portal
* Fixed - Attachment download issue in email piping
* Fixed - BetterDocs integration issue
* Fixed - Agent Only field isn't displaying into the ticket
* Fixed - Draft Reply approve button issue with attachment
* Fixed - There is an issue with exporting the agent report time
* Fixed - The Gravatar image link is causing a PHP 8.2 deprecated issue
* Fixed - The issue with the "Enable Reply from Telegram" button in Telegram
* Fixed - The Auto Close Settings are not saving
* Fixed - Helpscout ticket migration issue
* Fixed - When responding to emails in German, create a new ticket instead of replying.

= 1.7.72 (Date: January 10, 2024) =
* Fixed - Notification integration settings issue
* Fixed - Displaying an incorrect assigned agent name
* Fixed - Links open in same tab issue
* Fixed - Telegram reply issue
* Fixed - Required functionality is not working in the conditional field
* Fixed - Ticket status issue

= 1.7.71 (Date: December 23, 2023) =
* Fixed - Email Piping Ticket Created Discord Notification Issue fixed

= 1.7.7 (Date: December 13, 2023) =
* Added - Trigger Fluent CRM automation within workflow (Pro)
* Added - Agent feedback in ticket response (Pro)
* Added - Agent permission for save response as draft
* Added - New shortcode for agent title signature in the inbox settings
* Added - Custom registration field using hooks
* Fixed - Agent can assign ticket without permission
* Fixed - The time duration displayed for ticket creation and response creation is inconsistent
* Fixed - Open a new thread in email for every response created
* Fixed - Translation issue
* MySQL orderby security issue fixed

= 1.7.6 (Date: November 07, 2023) =
* Improved File Upload
* Dropbox and Google Drive File Upload Issues Fixed
* Full Rewrite of the File Upload & Remote Driver System
* Improved UI

= 1.7.5 (Date: November 01, 2023) =
* Fixed - Ticket id not included in outgoing webhook
* Fixed - Update and delete issue in saved reply
* Fixed - Time difference issue in saved reply

= 1.7.4 (Date: October 31, 2023) =
* Fixed - Freshdesk migrator issue
* Fixed - Added a few missing translations
* Fixed - Summary report issue fixing for products and business inbox
* Fixed - File upload and view issue for 3rd party

= 1.7.3 (Date: August 23, 2023) =
* Added - Report by Product(Pro)
* Added - Report by Business Inbox(Pro)
* Fixed - Create ticket issue for required fields is fixed
* Fixed - Custom field not showing in the add field from
* Fixed - Added missing translations

= 1.7.2 (Date: July 17, 2023) =
* Fixed - Create ticket issue for required fields is fixed
* Fixed - Custom field not showing in the add field from

= 1.7.0 (Date: July 14, 2023) =
* Added - Support email cc
* Added - Option to set dedicated mailbox for webhook
* Added - Business box added in the workflow action and condition list
* Added - Support file attachment upload in third party (Google Drive and Dropbox)
* Added - Zendesk migrator
* Fixed - Work action ordering issue
* Fixed - Custom field required in conditional form
* Fixed - Conditional form rendering issue
* Fixed - Ticket create using API endpoint
* Fixed - Freshdesk migrator issue

= 1.6.9 (Date: March 16, 2023) =
* Added - Custom field required or optional
* Added - Custom field in the workflow condition
* Added - Saved replies templates in auto ticket close module
* Added - Saved replies templates in the workflow
* Fixed - Fluent CRM widget missing issue
* Fixed - Ticket merge popup issue
* Fixed - Delete action of manual workflow
* More improvements

= 1.6.8 (Date: February 14, 2023) =
* Added - Migrate Tickets from Freshdesk
* Added - Toggle to stop auto close bookmarked tickets
* Fixed - Issue with telegram reply
* Fixed - Support staff not assigned to ticket via workflow
* Fixed - Frontend agent portal issues
* More Bug Fixes and Improvements

= 1.6.7 (Date: November 24, 2022) =
* Agent Summary Exporter
* Migrate Tickets from Help Scout
* WooCommerce Purchase History Widget Redesigned
* Bug Fixes and Improvements

= 1.6.6 (Date: October, 2022) =
* Activity Log Filters
* Active Tickets for Products
* Waiting Ticket stat on Dashboard
* Hourly Reports for tickets activity
* New Trigger – Ticket Closed on Automation
* Close Ticket Silently (without triggering emails)
* Migrate Tickets from Awesome Support
* Migrate Tickets from SupportCandy
* Bug Fixes and Improvements

= 1.6.5 (Date: August 24, 2022) =
* Added Auto Close Ticket Module based on ticket inactivity days
* Improved Saved Replies. Now you can add more replies
* Fixed File Upload Issues
* Fixed Few minor issues on integrations

= 1.6.2 (Date: August 22, 2022) =
* Fixed fiw minor bugs regarding data sanitizations
* Saved Replied issues Fixed
* All external links are will open in new tab
* Auto Linking linkable contents
* Create new ticket flow improved

= 1.6.0 (Date: August 19, 2022) =
* NEW - Agent portal in frontend
* Added - Shortcode support in workflow
* Added - LearnPress integration
* Added - Split reply to a new ticket
* Added - License status in EDD widget
* Added - Ticket closing feature from Slack and Telegram
* Added - Adding or removing ticket bookmark from workflow
* Improvement - Security
* Improvement - Code Base

= 1.5.7 (Date: July 07, 2022) =
* Added - Global Customer Searching on Ticket Creation on Behalf of Customer
* Added - Template for Ticket Creation on Behalf of Customer
* Fixed - WooCommerce Order Total Issue
* Fixed - Text Encoding Issue on Email Piping

= 1.5.6 (Date: May 26, 2022) =
* Added - Ticket Merge System
* Added - Ticket Watcher System
* Added - Mailbox Check in Workflow
* Added - FluentCRM List & Tag Check in Workflow
* Added - FluentCRM List & Tag Attach & Detach in Workflow
* Fixed - WooCommerce Multi Currency Issue
* Fixed - WooCommerce Draft Product Display in Custom Fields

= 1.5.5 (Date: March 02, 2022) =
* Added - Whatsapp integration via twilio
* Added - Outgoing Webhook Integration in workflow
* Added - Agents report filtering by specific agent
* Added - Today's stats of tickets
* Added - Send notification to 3rd party integrated notification system on agent assign
* Added - Ticket moving feature from one mailbox to another
* Fixed - Ticket created email notification not sending when creating a ticket via incoming webhook

= 1.5.4 (Date: January 19, 2022) =
* Added - Ticket advanced filtering
* Added - Custom fields on Telegram integration
* Added - Incoming Webhook
* Added - Missing translations
* Fixed - Issues related to email piping
* Fixed - Email footer not sending to email notification
* Fixed - Discord Notification issues
* Fixed - Custom fields not saving when creating a ticket from agent dashboard
