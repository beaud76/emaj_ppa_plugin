<?php

	/**
	 * english language file for E-maj extension of phpPgAdmin.
	 */

	// Basic strings 
	$plugin_lang['emajplugin'] = 'E-Maj plugin';
	$plugin_lang['emajnotavail'] = 'Sorry, E-Maj is not available for this database.';
	$plugin_lang['emajtooold'] = 'Sorry, this E-Maj version (%s) is too old. The minimum version supported by this plugin is %s.';
	$plugin_lang['emajstate'] = 'State';
	$plugin_lang['emajgroupnotidle'] = 'The tables group is not IDLE anymore.';
	$plugin_lang['emajgroupnotlogging'] = 'The tables group is not LOGGING anymore.';
	$plugin_lang['emajnoselectedgroup'] = 'No tables group has been selected!';
	$plugin_lang['emajgroup'] = 'Group';
	$plugin_lang['emajgroups'] = 'Groups';
	$plugin_lang['emajmark'] = 'Mark';
	$plugin_lang['emajmarks'] = 'Marks';
	$plugin_lang['emajgrouptype'] = 'Group type';
	$plugin_lang['emajrollbacktype'] = 'Rollback type';
	$plugin_lang['emajauditonly'] = 'AUDIT-ONLY';
	$plugin_lang['emajrollbackable'] = 'ROLLBACK-ABLE';
	$plugin_lang['emajunlogged'] = 'unlogged';
	$plugin_lang['emajlogged'] = 'logged';
	$plugin_lang['emajlogging'] = 'Logging';
	$plugin_lang['emajidle'] = 'Idle';
	$plugin_lang['emajactive'] = 'Active';
	$plugin_lang['emajdeleted'] = 'Deleted';
	$plugin_lang['emajpagebottom'] = 'Go to bottom';
	$plugin_lang['emajlogsize'] = 'Log size';
	$plugin_lang['emajrequired'] = 'Required';
	$plugin_lang['emajestimates'] = 'Estimates';
	$plugin_lang['emajfrom'] = 'From';
	$plugin_lang['emajto'] = 'To';

	// E-Maj tabs
	$plugin_lang['emajenvir'] = 'E-Maj env.';
	$plugin_lang['emajgroupsconf'] = 'Groups conf.';
	$plugin_lang['emajrlbkop'] = 'Rollback op.';
	$plugin_lang['emajlogstat'] = 'Log statistics';

	// E-Maj environment
	$plugin_lang['emajenvironment'] = 'E-Maj environment';
	$plugin_lang['emajcharacteristics'] = 'Characteristics';
	$plugin_lang['emajversion'] = 'E-Maj version: ';
	$plugin_lang['emajdiskspace'] = 'Disk space used by the E-Maj environment: %s of the current database.';
	$plugin_lang['emajchecking'] = 'E-Maj environment consistency';
	$plugin_lang['emajdiagnostics'] = 'Diagnostics';

	// Groups' content setup
	$plugin_lang['emajgroupsconfiguration'] = 'Tables groups\' configuration';
	$plugin_lang['emajschemaslist'] = 'Application schemas list';
	$plugin_lang['emajunknownobject'] = 'This object is referenced in the emaj_group_def table but is not created.';
	$plugin_lang['emajtblseqofschema'] = 'Tables and sequences in schema "%s"';
	$plugin_lang['emajassign'] = 'Assign';
	$plugin_lang['emajremove'] = 'Remove';
	$plugin_lang['emajlogschemasuffix'] = 'Log schema suffix';
	$plugin_lang['emajlogdattsp'] = 'Log tablespace';
	$plugin_lang['emajlogidxtsp'] = 'Log index tablespace';
	$plugin_lang['emajnewgroup'] = '-- new group --';
	$plugin_lang['emajnewsuffix'] = '-- new suffix --';
	$plugin_lang['emajnewtsp'] = '-- new tablespace --';
	$plugin_lang['emajspecifytblseqtoassign'] = 'Specify at least one table or sequence to assign';
	$plugin_lang['emajtblseqyetgroup'] = 'Error, " %s.%s " is already assigned to a tables group.';
	$plugin_lang['emajassignatblseq'] = 'E-Maj: Assign tables / sequences to a tables group';
	$plugin_lang['emajconfirmassigntblseq'] = 'Assign: %s';
	$plugin_lang['emajenterpriority'] = 'Processing priority';
	$plugin_lang['emajpriorityhelp'] = 'Tables and sequences processed in priority ascending order';
	$plugin_lang['emajenterlogschema'] = 'Log schema suffix';
	$plugin_lang['emajlogschemahelp'] = 'Log schema = \'emaj\' + suffix';
	$plugin_lang['emajenterlogdattsp'] = 'Log table tablespace';
	$plugin_lang['emajenterlogidxtsp'] = 'Log index tablespace';
	$plugin_lang['emajspecifytblseqtoremove'] = 'Specify at least one table or sequence to remove';
	$plugin_lang['emajtblseqnogroup'] = 'Error, " %s.%s " is not currently assigned to any tables group.';
	$plugin_lang['emajremoveatblseq'] = 'E-Maj: Remove tables / sequences from a tables group';
	$plugin_lang['emajconfirmremovetblseq'] = 'Are you sure you want to remove " %s.%s " from tables group "%s" ?';
	$plugin_lang['emajmodifygroupok'] = 'The change is recorded. It will take effect when the concerned tables groups will be (re)created.';
	$plugin_lang['emajmodifygrouperr'] = 'Error while updating tables groups content.';

	// List Groups
	$plugin_lang['emajgrouplist'] = 'Tables groups list';
	$plugin_lang['emajidlegroups'] = 'Tables groups in "IDLE" state ';
	$plugin_lang['emajlogginggroups'] = 'Tables groups in "LOGGING" state ';
	$plugin_lang['emajcreationdatetime'] = 'Creation date/time';
	$plugin_lang['emajnbtbl'] = '# tables';
	$plugin_lang['emajnbseq'] = '# sequences';
	$plugin_lang['emajnbmark'] = '# marks';
	$plugin_lang['emajdetail'] = 'Detail';
	$plugin_lang['emajsetmark'] = 'Set a mark';
	$plugin_lang['emajsetcomment'] = 'Set a comment';
	$plugin_lang['emajnoidlegroup'] = 'No Emaj tables group is currently in idle state.';
	$plugin_lang['emajnologginggroup'] = 'No Emaj tables group is currently in logging state.';
	$plugin_lang['emajcreategroup'] = 'Creation of a new tables group';

	// Rollback activity
	$plugin_lang['emajrlbkoperations'] = 'E-Maj Rollbacks';
	$plugin_lang['emajrlbkid'] = 'Rlbk Id.';
	$plugin_lang['emajrlbkstart'] = 'Rollback start';
	$plugin_lang['emajrlbkend'] = 'Rollback end';
	$plugin_lang['emajduration'] = 'Duration';
	$plugin_lang['emajmarksetat'] = 'Mark set at';
	$plugin_lang['emajislogged'] = 'Logged ?';
	$plugin_lang['emajnbsession'] = 'Nb sessions';
	$plugin_lang['emajnbproctable'] = 'Nb processed tables';
	$plugin_lang['emajnbprocseq'] = 'Nb processed sequences';
	$plugin_lang['emajcurrentduration'] = 'Current duration';
	$plugin_lang['emajestimremaining'] = 'Estimated remaining';
	$plugin_lang['emajpctcompleted'] = '% completed';
	$plugin_lang['emajcompletedrlbk'] = 'Completed E-Maj rollbacks';
	$plugin_lang['emajinprogressrlbk'] = 'In progress E-Maj rollbacks';
	$plugin_lang['emajnbtabletoprocess'] = 'Nb tables to process';
	$plugin_lang['emajnbseqtoprocess'] = 'Nb sequences to process';
	$plugin_lang['emajnorlbk'] = 'No E-Maj rollback.';
	$plugin_lang['emajfilterrlbk1'] = 'Display the';
	$plugin_lang['emajfilterrlbk2'] = 'most recent';
	$plugin_lang['emajfilterrlbk3'] = 'completed since less than';
	$plugin_lang['emajfilterrlbk4'] = 'hours';
	$plugin_lang['emajfilter'] = 'Filter';

	// Group's properties and marks
	$plugin_lang['emajgrouppropertiesmarks'] = 'Tables group "%s" properties and marks';
	$plugin_lang['emajgroupproperties'] = 'Tables group "%s" properties';
	$plugin_lang['emajcontent'] = 'Content';
	$plugin_lang['emajgroupmarks'] = 'Tables group "%s" marks';
	$plugin_lang['emajtimestamp'] = 'Date/Time';
	$plugin_lang['emajnbupdates'] = '# row updates';	
	$plugin_lang['emajcumupdates'] = 'Cumulative updates';
	$plugin_lang['emajsimrlbk'] = 'Simulate Rollback';
	$plugin_lang['emajrlbk'] = 'Rollback';
	$plugin_lang['emajfirstmark'] = 'First mark';
	$plugin_lang['emajrename'] = 'Rename';
	$plugin_lang['emajnomark'] = 'The tables group has no mark';

	// Statistics
	$plugin_lang['emajshowstat'] = 'Statistics from E-Maj log for group "%s"';
	$plugin_lang['emajnoupdate'] = 'No update for this tables group'; 
	$plugin_lang['emajcurrentsituation'] = 'Current situation';
	$plugin_lang['emajdetailedstat'] = 'Detailed stats';
	$plugin_lang['emajdetailedlogstatwarning'] = 'Attention, scanning the log tables needed to get detailed statistics may take a long time';
	$plugin_lang['emajlogstatcurrentsituation'] = 'the current situation';
	$plugin_lang['emajlogstatmark'] = 'mark "%s"';
	$plugin_lang['emajlogstattittle'] = 'Table updates between mark "%s" and %s for tables group "%s"';
	$plugin_lang['emajsimrlbkduration'] = 'Rolling the tables group "%s" back to mark "%s" would take about %s.';
	$plugin_lang['emajstatverb'] = 'SQL verb';
	$plugin_lang['emajnbinsert'] = '# INSERT';
	$plugin_lang['emajnbupdate'] = '# UPDATE';
	$plugin_lang['emajnbdelete'] = '# DELETE';
	$plugin_lang['emajnbtruncate'] = '# TRUNCATE';
	$plugin_lang['emajnbrole'] = '# roles';
	$plugin_lang['emajstatrows'] = '# row updates';
	$plugin_lang['emajbackgroup'] = 'Go back to the tables group';

	// Group's content
	$plugin_lang['emajgroupcontent'] = 'Content of tables group "%s"';
	$plugin_lang['emajpriority'] = 'Priority';
	$plugin_lang['emajlogschema'] = 'Log schema';
	$plugin_lang['emajlogdattsp'] = 'Log tablespace';
	$plugin_lang['emajlogidxtsp'] = 'Log index tablespace';

	// Group creation
	$plugin_lang['emajcreateagroup'] = 'E-Maj: Create a tables group';
	$plugin_lang['emajconfirmcreategroup'] = 'Are you sure you want to create tables group "%s" ?';
	$plugin_lang['emajcreategroupok'] = 'The tables group "%s" has been created.';
	$plugin_lang['emajcreategrouperr'] = 'Error during group "%s" creation!';

	// Group drop
	$plugin_lang['emajdropagroup'] = 'E-Maj: Drop a tables group';
	$plugin_lang['emajconfirmdropgroup'] = 'Are you sure you want to drop tables group "%s" ?';
	$plugin_lang['emajcantdrpgroup'] = 'It cannot be dropped.';
	$plugin_lang['emajdropgroupok'] = 'The tables group "%s" has been dropped.';
	$plugin_lang['emajdropgrouperr'] = 'Error during tables group "%s" drop!';

	// Group alter
	$plugin_lang['emajalteragroup'] = 'E-Maj: Alter a tables group';
	$plugin_lang['emajconfirmaltergroup'] = 'Are you sure you want to alter tables group "%s" ?';
	$plugin_lang['emajcantaltergroup'] = 'It cannot be altered.';
	$plugin_lang['emajaltergroupok'] = 'The tables group "%s" has been altered.';
	$plugin_lang['emajalternogroup'] = 'No detected change for the tables group "%s".';
	$plugin_lang['emajaltergrouperr'] = 'Error during tables group "%s" alter!';

	// Group comment
	$plugin_lang['emajcommentagroup'] = 'E-Maj: Record a comment for a tables group';
	$plugin_lang['emajcommentgroup'] = 'Enter, modify or erase the comment for tables group "%s".';
	$plugin_lang['emajcommentgroupok'] = 'The comment for tables group "%s" has been recorded.';
	$plugin_lang['emajcommentgrouperr'] = 'Error during comment recording for tables group "%s"!';

	// Group start
	$plugin_lang['emajstartagroup'] = 'E-Maj: Start a tables group';
	$plugin_lang['emajconfirmstartgroup'] = 'Start tables group "%s"';
	$plugin_lang['emajinitmark'] = 'Initial mark';
	$plugin_lang['emajoldlogsdeletion'] = 'Old logs deletion';
//	$plugin_lang['emajinitmarkerr'] = 'An initial mark name must be supplied';
	$plugin_lang['emajcantstartgroup'] = 'Tables group "%s" is already in logging state.';
	$plugin_lang['emajstartgroupok'] = 'Tables group "%s" is started with mark "%s".';
	$plugin_lang['emajstartgrouperr'] = 'Error during tables group "%s" start!';

	// Groups start
	$plugin_lang['emajstartgroups'] = 'E-Maj: Start tables groups';
	$plugin_lang['emajconfirmstartgroups'] = 'Start tables groups "%s"';
	$plugin_lang['emajcantstartgroups'] = ' Can\'t start tables groups "%s". Group "%s" is already started.';
	$plugin_lang['emajstartgroupsok'] = 'Tables groups "%s" are started with mark "%s".';
	$plugin_lang['emajstartgroupserr'] = 'Error during tables groups "%s" start!';

	// Group stop
	$plugin_lang['emajstopagroup'] = 'E-Maj: Stop a tables group';
	$plugin_lang['emajconfirmstopgroup'] = 'Stop tables group "%s"';
	$plugin_lang['emajstopmark'] = 'Final mark';
	$plugin_lang['emajforcestop'] = 'Forced stop (in case of problem only)';
	$plugin_lang['emajcantstopgroup'] = 'Tables group "%s" is already idle.';
	$plugin_lang['emajstopgroupok'] = 'Tables group "%s" has been stopped.';
	$plugin_lang['emajstopgrouperr'] = 'Error during tables group "%s" stop!';

	// Groups stop
	$plugin_lang['emajstopgroups'] = 'E-Maj: Stop tables groups';
	$plugin_lang['emajconfirmstopgroups'] = 'Stop tables groups "%s"';
	$plugin_lang['emajcantstopgroups'] = 'Can\'t stop tables groups "%s". Group "%s" is already stopped.';
	$plugin_lang['emajstopgroupsok'] = 'Tables groups "%s" have been stopped.';
	$plugin_lang['emajstopgroupserr'] = 'Error during tables groups "%s" stop!';

	// Group reset
	$plugin_lang['emajresetagroup'] = 'E-Maj: Reset a tables group';
	$plugin_lang['emajconfirmresetgroup'] = 'Are you sure you want to reset tables group "%s" ?';
	$plugin_lang['emajcantresetgroup'] = 'It cannot be reset.';
	$plugin_lang['emajresetgroupok'] = 'Tables group "%s" has been reset.';
	$plugin_lang['emajresetgrouperr'] = 'Error during tables group "%s" reset!';

	// Set Mark for one or several groups
	$plugin_lang['emajsetamark'] = 'E-Maj: Set a mark';
	$plugin_lang['emajconfirmsetmarkgroup'] = 'Set a mark for tables group(s) "%s":';
//	$plugin_lang['emajmarkerr'] = 'A mark name must be supplied';
	$plugin_lang['emajcantsetmark'] = 'No mark can be set.';
	$plugin_lang['emajinvalidmark'] = 'The supplied mark (%s) is invalid!';
	$plugin_lang['emajsetmarkgroupok'] = 'The mark "%s" has been set for tables group(s) "%s".';
	$plugin_lang['emajsetmarkgrouperr'] = 'Error during mark set "%s" for tables group(s) "%s"!';
	$plugin_lang['emajcantsetmarkgroups'] = 'Can\'t set a mark for tables groups "%s". Group "%s" is stopped.';

	// Comment mark
	$plugin_lang['emajcommentamark'] = 'E-Maj: Record a comment for a mark';
	$plugin_lang['emajcommentmark'] = 'Enter, modify or erase the comment for mark "%s" of tables group "%s"';
	$plugin_lang['emajcommentmarkok'] = 'The comment for mark "%s" of tables group "%s" has been recorded.';
	$plugin_lang['emajcommentmarkerr'] = 'Error during comment recording for mark "%s" of tables group "%s"!';

	// Group rollback
	$plugin_lang['emajrlbkagroup'] = 'E-Maj: Rollback a tables group';
	$plugin_lang['emajconfirmrlbkgroup'] = 'Rollback tables group "%s" to mark "%s"';
	$plugin_lang['emajselectmarkgroup'] = 'Rollback tables group "%s" to mark : ';
	$plugin_lang['emajinvalidrlbkmark'] = 'Mark "%s" is not valid anymore.';
	$plugin_lang['emajcantrlbkgroup'] = 'It cannot be rollbacked!';
	$plugin_lang['emajrlbkgroupok'] = 'Tables group "%s" has been rollbacked to mark "%s".';
	$plugin_lang['emajrlbkgrouperr'] = 'Error during tables group "%s" rollback to mark "%s"!';

	// Groups rollback
	$plugin_lang['emajrlbkgroups'] = 'E-Maj: Rollback tables groups';
	$plugin_lang['emajselectmarkgroups'] = 'Rollback tables groups "%s" to mark : ';
	$plugin_lang['emajnomarkgroups'] = 'No common mark for tables groups "%s" can be used for a rollback.';
	$plugin_lang['emajcantrlbkgroups'] = 'Rollback tables groups "%s" is impossible. Group "%s" is IDLE.';
	$plugin_lang['emajrlbkgroupsok'] = 'Tables groups "%s" have been rollbacked to mark "%s".';
	$plugin_lang['emajrlbkgroupserr'] = 'Error during tables groups "%s" rollback to mark "%s"!';

	// Mark renaming
	$plugin_lang['emajrenameamark'] = 'E-Maj : Rename a mark';
	$plugin_lang['emajconfirmrenamemark'] = 'Renaming mark "%s" of tables group "%s"';
	$plugin_lang['emajnewnamemark'] = 'New name';
	$plugin_lang['emajrenamemarkok'] = 'Mark "%s" of tables group "%s" has been renamed into "%s".';
	$plugin_lang['emajrenamemarkerr'] = 'Error during renaming mark "%s" of tables group "%s" into "%s"!';

	// Mark deletion
	$plugin_lang['emajdelamark'] = 'E-Maj: Delete a mark';
	$plugin_lang['emajconfirmdelmark'] = 'Are you sure you want to delete the mark "%s" for tables group "%s" ?';
	$plugin_lang['emajcantdelmark'] = 'The mark cannot be deleted!';
	$plugin_lang['emajdelmarkok'] = 'Mark "%s" has been deleted for tables group "%s".';
	$plugin_lang['emajdelmarkerr'] = 'Error during mark "%s" deletion for tables group "%s"!';

	// Marks before mark deletion
	$plugin_lang['emajdelmarks'] = 'E-Maj: Delete marks';
	$plugin_lang['emajconfirmdelmarks'] = 'Are you sure you want to delete all marks preceeding mark "%s" for tables group "%s" ?';
	$plugin_lang['emajdelmarksok'] = 'All (%s) marks preceeding mark "%s" have been deleted for tables group "%s".';
	$plugin_lang['emajdelmarkserr'] = 'Error during the deletion of marks preceeding mark "%s" for tables group "%s"!';
?>
