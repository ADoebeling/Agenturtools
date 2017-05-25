<?php

/** Username and token of the github-user that shall create the reminders */
defined ('githubUser') or define ('githubUser', 'YOUR-BOT-USERNAME', true);
defined ('githubToken') or define ('githubToken', 'YOUR-BOT-TOKEN', true);

/** Owner and name of the repository that shall receive the GitSniffer-notifications */
defined ('githubToken') or define ('githubRepoOwner', 'YOU-PROJECT-REPO-OWNER', true);
defined ('githubRepo') or define ('githubRepo', 'YOUR-PROJECT-REPO-NAME', true);

/** List of users that shall receive github-notifications (plain, space-separated) */
defined('reminderNotification') or define ('reminderNotification', '@example', true);