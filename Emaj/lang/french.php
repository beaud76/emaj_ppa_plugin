<?php

	/**
	 * french language file for E-maj extension of phpPgAdmin.
	 */

	// Basic strings
	$plugin_lang['emajplugin'] = 'Plugin E-Maj';
	$plugin_lang['emajnotavail'] = 'Désolé, E-Maj n\'est pas disponible sur cette base de données.';
	$plugin_lang['emajtooold'] = 'Désolé, cette version d\'E-Maj (%s) est trop ancienne. La version minimum supportée par ce plugin est %s.';
	$plugin_lang['emajstate'] = 'Etat';
	$plugin_lang['emajgroupnotidle'] = 'Le groupe de tables n\'est plus "arrêté".';
	$plugin_lang['emajgroupnotlogging'] = 'Le groupe de tables n\'est plus "démarré".';
	$plugin_lang['emajnoselectedgroup'] = 'Aucun groupe de tables n\'a été sélectionné !';
	$plugin_lang['emajgroup'] = 'Groupe';
	$plugin_lang['emajgroups'] = 'Groupes';
	$plugin_lang['emajmark'] = 'Marque';
	$plugin_lang['emajmarks'] = 'Marques';
	$plugin_lang['emajgrouptype'] = 'Type de groupe';
	$plugin_lang['emajrollbacktype'] = 'Type de rollback';
	$plugin_lang['emajauditonly'] = 'Audit-seul';
	$plugin_lang['emajrollbackable'] = 'Rollbackable';
	$plugin_lang['emajunlogged'] = 'non tracé';
	$plugin_lang['emajlogged'] = 'tracé';
	$plugin_lang['emajlogging'] = 'Démarré';
	$plugin_lang['emajidle'] = 'Arrêté';
	$plugin_lang['emajactive'] = 'Active';
	$plugin_lang['emajdeleted'] = 'Supprimée';
	$plugin_lang['emajpagebottom'] = 'Bas de la page';
	$plugin_lang['emajlogsize'] = 'Taille du log';
	$plugin_lang['emajrequired'] = 'Requis';
	$plugin_lang['emajestimates'] = 'Estimations';
	$plugin_lang['emajfrom'] = 'De';
	$plugin_lang['emajto'] = 'A';

	// E-Maj tabs
	$plugin_lang['emajenvir'] = 'Envir. E-Maj';
	$plugin_lang['emajgroupsconf'] = 'Config. groupes';
	$plugin_lang['emajrlbkop'] = 'Rollbacks';
	$plugin_lang['emajlogstat'] = 'Statistiques log';

	// E-Maj environment
	$plugin_lang['emajenvironment'] = 'Environnement E-Maj';
	$plugin_lang['emajcharacteristics'] = 'Caractéristiques';
	$plugin_lang['emajversion'] = 'Version E-Maj : ';
	$plugin_lang['emajdiskspace'] = 'Place disque occupée par l\'environnement E-Maj : %s de la base de données courante.';
	$plugin_lang['emajchecking'] = 'Intégrité de l\'environnement E-Maj';
	$plugin_lang['emajdiagnostics'] = 'Diagnostics';

	// Groups' content setup
	$plugin_lang['emajgroupsconfiguration'] = 'Configuration des groupes de tables';
	$plugin_lang['emajschemaslist'] = 'Liste des schémas applicatifs';
	$plugin_lang['emajunknownobject'] = 'Cet objet est référencé dans la table emaj_group_def mais n\'est pas créé.';
	$plugin_lang['emajtblseqofschema'] = 'Tables et séquences du schéma "%s"';
	$plugin_lang['emajassign'] = 'Affecter';
	$plugin_lang['emajremove'] = 'Retirer';
	$plugin_lang['emajlogschemasuffix'] = 'Suffixe schéma log';
	$plugin_lang['emajlogdattsp'] = 'Tablespace log';
	$plugin_lang['emajlogidxtsp'] = 'Tablespace index log';
	$plugin_lang['emajnewgroup'] = '-- nouveau groupe --';
	$plugin_lang['emajnewsuffix'] = '-- nouveau suffixe --';
	$plugin_lang['emajnewtsp'] = '-- nouveau tablespace --';
	$plugin_lang['emajspecifytblseqtoassign'] = 'Spécifier au moins une table ou séquence à affecter';
	$plugin_lang['emajtblseqyetgroup'] = 'Erreur, " %s.%s " est déjà affecté à un groupe de tables.';
	$plugin_lang['emajassignatblseq'] = 'E-Maj : Affecter des tables / séquences à un groupe de tables';
	$plugin_lang['emajconfirmassigntblseq'] = 'Affecter : %s';
	$plugin_lang['emajenterpriority'] = 'Priorité de traitement ';
	$plugin_lang['emajpriorityhelp'] = 'Tables et séquences traitées par ordre croissant de priorité';
	$plugin_lang['emajenterlogschema'] = 'Suffixe du schéma de log ';
	$plugin_lang['emajlogschemahelp'] = 'Schema de log = \'emaj\' + suffixe';
	$plugin_lang['emajenterlogdattsp'] = 'Tablespace pour la table de log ';
	$plugin_lang['emajenterlogidxtsp'] = 'Tablespace pour l\'index de la table de log ';
	$plugin_lang['emajspecifytblseqtoremove'] = 'Spécifier au moins une table ou séquence à retirer';
	$plugin_lang['emajtblseqnogroup'] = 'Erreur, " %s.%s " n\'est actuellement affecté à aucun groupe de tables.';
	$plugin_lang['emajremoveatblseq'] = 'E-Maj : Retirer des tables / séquences d\'un groupe de tables';
	$plugin_lang['emajconfirmremovetblseq'] = 'êtes-vous sûr de vouloir retirer " %s.%s " du groupe de tables "%s" ?';
	$plugin_lang['emajmodifygroupok'] = 'La modification est enregistrée. Elle sera effective après (re)création des groupes de tables concernés.';
	$plugin_lang['emajmodifygrouperr'] = 'Erreur lors du changement de composition des groupes de tables.';

	// List Groups
	$plugin_lang['emajgrouplist'] = 'Liste des groupes de tables';
	$plugin_lang['emajidlegroups'] = 'Groupes de tables en état "arrêté" ';
	$plugin_lang['emajlogginggroups'] = 'Groupes de tables en état "démarré" ';
	$plugin_lang['emajcreationdatetime'] = 'Date/heure de création';
	$plugin_lang['emajnbtbl'] = 'Nb tables';
	$plugin_lang['emajnbseq'] = 'Nb séquences';
	$plugin_lang['emajnbmark'] = 'Nb marques';
	$plugin_lang['emajdetail'] = 'Détail';	
	$plugin_lang['emajsetmark'] = 'Poser une marque';
	$plugin_lang['emajsetcomment'] = 'Commenter';
	$plugin_lang['emajnoidlegroup'] = 'Il n\'y a actuellement aucun groupe de tables en état "arrêté".';
	$plugin_lang['emajnologginggroup'] = 'Il n\'y a actuellement aucun groupe de tables en état "démarré".';
	$plugin_lang['emajcreategroup'] = 'Création d\'un nouveau groupe de tables';

	// Rollback activity
	$plugin_lang['emajrlbkoperations'] = 'Rollbacks E-Maj';
	$plugin_lang['emajrlbkid'] = 'Id. Rlbk';
	$plugin_lang['emajrlbkstart'] = 'Début rollback';
	$plugin_lang['emajrlbkend'] = 'Fin rollback';
	$plugin_lang['emajduration'] = 'Durée';
	$plugin_lang['emajmarksetat'] = 'Marque posée à';
	$plugin_lang['emajislogged'] = 'Tracé ?';
	$plugin_lang['emajnbsession'] = 'Nb sessions';
	$plugin_lang['emajnbproctable'] = 'Nb tables traitées';
	$plugin_lang['emajnbprocseq'] = 'Nb séquences traitées';
	$plugin_lang['emajcurrentduration'] = 'Durée actuelle';
	$plugin_lang['emajestimremaining'] = 'Restant estimée';
	$plugin_lang['emajpctcompleted'] = '% effectué';
	$plugin_lang['emajnbtabletoprocess'] = 'Nb tables à traiter';
	$plugin_lang['emajnbseqtoprocess'] = 'Nb séquences à traiter';
	$plugin_lang['emajcompletedrlbk'] = 'Rollbacks E-Maj terminés';
	$plugin_lang['emajinprogressrlbk'] = 'Rollbacks E-Maj en cours';
	$plugin_lang['emajnorlbk'] = 'Aucun rollback.';
	$plugin_lang['emajfilterrlbk1'] = 'Afficher les';
	$plugin_lang['emajfilterrlbk2'] = 'plus récents';
	$plugin_lang['emajfilterrlbk3'] = 'terminés depuis moins de';
	$plugin_lang['emajfilterrlbk4'] = 'heures';
	$plugin_lang['emajfilter'] = 'Filtrer';

	// Group's properties and marks
	$plugin_lang['emajgrouppropertiesmarks'] = 'Propriétés et marques du groupe de tables "%s"';
	$plugin_lang['emajgroupproperties'] = 'Propriétés du groupe de tables "%s"';
	$plugin_lang['emajcontent'] = 'Contenu';
	$plugin_lang['emajgroupmarks'] = 'Marques du groupe de tables "%s"';
	$plugin_lang['emajtimestamp'] = 'Date-Heure';
	$plugin_lang['emajnbupdates'] = 'Nb mises à jour';	
	$plugin_lang['emajcumupdates'] = 'Cumul mises à jour';	
	$plugin_lang['emajsimrlbk'] = 'Simuler Rollback';
	$plugin_lang['emajrlbk'] = 'Rollback';
	$plugin_lang['emajfirstmark'] = 'Première marque';
	$plugin_lang['emajrename'] = 'Renommer';
	$plugin_lang['emajnomark'] = 'Le groupe de tables n\'a pas de marque';

	// Statistics
	$plugin_lang['emajshowstat'] = 'Statistiques issues du log E-Maj pour le groupe "%s"';
	$plugin_lang['emajnoupdate'] = 'Aucune mise à jour pour ce groupe de tables';
	$plugin_lang['emajcurrentsituation'] = 'Situation courante';
	$plugin_lang['emajdetailedstat'] = 'Stats détaillées';
	$plugin_lang['emajdetailedlogstatwarning'] = 'Attention, le parcours des tables de log nécessaires à l\'obtention des statistiques détaillées peut être long';
	$plugin_lang['emajlogstatcurrentsituation'] = 'la situation courante';
	$plugin_lang['emajlogstatmark'] = 'la marque "%s"';
	$plugin_lang['emajlogstattittle'] = 'Mises à jour de table entre la marque "%s" et %s pour le groupe de tables "%s"';
	$plugin_lang['emajsimrlbkduration'] = 'Le rollback du groupe de tables "%s" à la marque "%s" durerait environ %s.';
	$plugin_lang['emajstatverb'] = 'Verbe SQL';
	$plugin_lang['emajnbinsert'] = 'Nb INSERT';
	$plugin_lang['emajnbupdate'] = 'Nb UPDATE';
	$plugin_lang['emajnbdelete'] = 'Nb DELETE';
	$plugin_lang['emajnbtruncate'] = 'Nb TRUNCATE';
	$plugin_lang['emajnbrole'] = 'Nb rôles';
	$plugin_lang['emajstatrows'] = 'Nb mises à jour';
	$plugin_lang['emajbackgroup'] = 'Revenir au groupe de tables';

	// Group's content
	$plugin_lang['emajgroupcontent'] = 'Contenu du groupe de tables "%s"';
	$plugin_lang['emajpriority'] = 'Priorité';
	$plugin_lang['emajlogschema'] = 'Schéma de log';
	$plugin_lang['emajlogdattsp'] = 'Tablespace log';
	$plugin_lang['emajlogidxtsp'] = 'Tablespace index log';

	// Group creation
	$plugin_lang['emajcreateagroup'] = 'E-Maj : Créer un groupe de tables';
	$plugin_lang['emajconfirmcreategroup'] = 'Etes-vous sur de vouloir créer le groupe de tables "%s" ?';
	$plugin_lang['emajcreategroupok'] = 'Le groupe de tables "%s" a été créé.';
	$plugin_lang['emajcreategrouperr'] = 'Erreur lors de la création du groupe de tables "%s" !';

	// Group drop
	$plugin_lang['emajdropagroup'] = 'E-Maj : Supprimer un groupe de tables';
	$plugin_lang['emajconfirmdropgroup'] = 'Etes-vous sur de vouloir supprimer le groupe de tables "%s" ?';
	$plugin_lang['emajcantdrpgroup'] = 'Il ne peut être supprimé.';
	$plugin_lang['emajdropgroupok'] = 'Le groupe de tables "%s" a été supprimé.';
	$plugin_lang['emajdropgrouperr'] = 'Erreur lors de la suppression du groupe de tables "%s" !';

	// Group alter
	$plugin_lang['emajalteragroup'] = 'E-Maj : Modifier un groupe de tables';
	$plugin_lang['emajconfirmaltergroup'] = 'êtes-vous sur de vouloir modifier le groupe de tables "%s" ?';
	$plugin_lang['emajcantaltergroup'] = 'Il ne peut être modifié.';
	$plugin_lang['emajaltergroupok'] = 'Le groupe de tables "%s" a été modifié.';
	$plugin_lang['emajalternogroup'] = 'Aucune modification détectée pour le groupe de table "%s".';
	$plugin_lang['emajaltergrouperr'] = 'Erreur lors de la modification du groupe de tables "%s" !';

	// Group comment
	$plugin_lang['emajcommentagroup'] = 'E-Maj : Enregistrer un commentaire pour un groupe de tables ';
	$plugin_lang['emajcommentgroup'] = 'Entrez, modifier ou supprimer un commentaire pour le groupe de tables "%s"';
	$plugin_lang['emajcommentgroupok'] = 'Le commentaire a été enregistré pour le groupe de tables "%s".';
	$plugin_lang['emajcommentgrouperr'] = 'Erreur lors de l\'enregistrement du commentaire pour le groupe de tables "%s" !';

	// Group start
	$plugin_lang['emajstartagroup'] = 'E-Maj : Démarrer un groupe de tables';
	$plugin_lang['emajconfirmstartgroup'] = 'Démarrer le groupe de tables "%s"';
	$plugin_lang['emajinitmark'] = 'Marque initiale';
	$plugin_lang['emajoldlogsdeletion'] = 'Suppression des anciens logs';
//	$plugin_lang['emajinitmarkerr'] = 'Il faut donner un nom à la marque initiale.';
	$plugin_lang['emajcantstartgroup'] = 'Le groupe de tables "%s" est déjà  démarré.';
	$plugin_lang['emajstartgroupok'] = 'Le groupe de tables "%s" est démarré avec la marque "%s".';
	$plugin_lang['emajstartgrouperr'] = 'Erreur lors du démarrage du groupe de tables "%s" !';	

	// Groups start
	$plugin_lang['emajstartgroups'] = 'E-Maj : Démarrer des groupes de tables';
	$plugin_lang['emajconfirmstartgroups'] = 'Démarrer les groupes de tables "%s"';
	$plugin_lang['emajcantstartgroups'] = 'Démarrage des groupes de tables "%s" impossible. Le groupe "%s" est déjà démarré.';
	$plugin_lang['emajstartgroupsok'] = 'Les groupes de tables "%s" ont été démarrés avec la marque "%s".';
	$plugin_lang['emajstartgroupserr'] = 'Erreur lors du démarrage des groupes de tables "%s" !';

	// Group stop
	$plugin_lang['emajstopagroup'] = 'E-Maj : Arrêter un groupe de tables ';
	$plugin_lang['emajconfirmstopgroup'] = 'Arrêter le groupe de tables "%s"';
	$plugin_lang['emajstopmark'] = 'Marque finale';
	$plugin_lang['emajforcestop'] = 'Forcer l\'arrêt (en cas de problème seulement)';
	$plugin_lang['emajcantstopgroup'] = 'Le groupe de tables "%s" est déjà arrêté.';
	$plugin_lang['emajstopgroupok'] = 'Le groupe de tables "%s" a été arrêté.';
	$plugin_lang['emajstopgrouperr'] = 'Erreur lors de l\'arrêt du groupe de tables "%s" !';

	// Groups stop
	$plugin_lang['emajstopgroups'] = 'E-Maj : Arrêter des groupes de tables';
	$plugin_lang['emajconfirmstopgroups'] = 'Arrêter les groupes de tables "%s"';
	$plugin_lang['emajcantstopgroups'] = 'Arrêt des groupes de tables "%s" impossible. Le groupe "%s" est déjà arrêté.';
	$plugin_lang['emajstopgroupsok'] = 'Les groupes de tables "%s" ont été arrêtés.';
	$plugin_lang['emajstopgroupserr'] = 'Erreur lors de l\'arrêt des groupes de tables "%s" !';

	// Group reset
	$plugin_lang['emajresetagroup'] = 'E-Maj : Réinitialiser un groupe de tables';
	$plugin_lang['emajconfirmresetgroup'] = 'êtes-vous sûr de vouloir réinitialiser le groupe de tables "%s" ?';
	$plugin_lang['emajcantresetgroup'] = 'Le groupe de tables ne peut être réinitialisé.';
	$plugin_lang['emajresetgroupok'] = 'Le groupe de tables "%s" est réinitialisé.';
	$plugin_lang['emajresetgrouperr'] = 'Erreur lors de la réinitialisation du groupe de tables "%s" !';

	// Set Mark for one or several groups
	$plugin_lang['emajsetamark'] = 'E-Maj : Poser une marque';
	$plugin_lang['emajconfirmsetmarkgroup'] = 'Pose d\'une marque pour le(s) groupe(s) de tables "%s" :';
//	$plugin_lang['emajmarkerr'] = 'Il faut donner un nom à la marque.';
	$plugin_lang['emajcantsetmark'] = 'Aucune marque ne peut être posée.';
	$plugin_lang['emajinvalidmark'] = 'La marque saisie (%s) est invalide.';
	$plugin_lang['emajsetmarkgroupok'] = 'La marque "%s" est posée pour le(s) groupe(s) de tables "%s".';
	$plugin_lang['emajsetmarkgrouperr'] = 'Erreur lors de la pose de la marque "%s" pour le(s) groupe(s) de tables "%s" !';
	$plugin_lang['emajcantsetmarkgroups'] = 'Pose d\'une marque pour les groupes de tables "%s" impossible. Le groupe "%s" est arrêté.';

	// Comment mark
	$plugin_lang['emajcommentamark'] = 'E-Maj : Enregistrer un commentaire pour une marque';
	$plugin_lang['emajcommentmark'] = 'Entrez, modifier ou supprimer le commentaire pour la marque "%s" du groupe de tables "%s".';
	$plugin_lang['emajcommentmarkok'] = 'Le commentaire a été enregistré pour la marque "%s" du groupe de tables "%s".';
	$plugin_lang['emajcommentmarkerr'] = 'Erreur lors de l\'enregistrement du commentaire pour la marque "%s" du groupe de tables "%s" !';

	// Group rollback
	$plugin_lang['emajrlbkagroup'] = 'E-Maj : Rollbacker un groupe de tables';
	$plugin_lang['emajconfirmrlbkgroup'] = 'Rollback du groupe de tables "%s" à la marque "%s"';
	$plugin_lang['emajselectmarkgroup'] = 'Rollback du groupe de tables "%s" à la marque : ';
	$plugin_lang['emajinvalidrlbkmark'] = 'La marque "%s" n\'est plus valide.';
	$plugin_lang['emajcantrlbkgroup'] = 'Le groupe de tables ne peut être rollbacké.';
	$plugin_lang['emajrlbkgroupok'] = 'Le rollback du groupe de tables "%s" à la marque "%s" est effectué.';
	$plugin_lang['emajrlbkgrouperr'] = 'Erreur lors du rollback du groupe de tables "%s" à la marque "%s" !';

	// Groups rollback
	$plugin_lang['emajrlbkgroups'] = 'E-Maj : Rollbacker des groupes de tables';
	$plugin_lang['emajselectmarkgroups'] = 'Rollback des groupes de tables "%s" à la marque : ';
	$plugin_lang['emajnomarkgroups'] = 'Aucune marque commune aux groupes de tables "%s" ne peut être utilisée pour un rollback.';
	$plugin_lang['emajcantrlbkgroups'] = 'Rollback des groupes de tables "%s" impossible. Le groupe "%s" est arrêté.';
	$plugin_lang['emajrlbkgroupsok'] = 'Le rollback des groupes de tables "%s" à la marque "%s" est effectué.';
	$plugin_lang['emajrlbkgroupserr'] = 'Erreur lors du rollback des groupes de tables "%s" à la marque "%s" !';

	// Mark renaming
	$plugin_lang['emajrenameamark'] = 'E-Maj : Renommer une marque';
	$plugin_lang['emajconfirmrenamemark'] = 'Renomage de la marque "%s" du groupe de tables "%s"';
	$plugin_lang['emajnewnamemark'] = 'Nouveau nom';
	$plugin_lang['emajrenamemarkok'] = 'La marque "%s" du groupe de tables "%s" a été renommée en "%s".';
	$plugin_lang['emajrenamemarkerr'] = 'Erreur lors du renommage de la marque "%s" du groupe de tables "%s" en "%s" !';

	// Mark deletion
	$plugin_lang['emajdelamark'] = 'E-Maj : Effacer une marque';
	$plugin_lang['emajconfirmdelmark'] = 'Etes-vous sûr de vouloir effacer la marque "%s" pour le groupe de tables "%s" ?';
	$plugin_lang['emajcantdelmark'] = 'La marque ne peut être effacée.';
	$plugin_lang['emajdelmarkok'] = 'La marque "%s" a été effacée pour le groupe de tables "%s".';
	$plugin_lang['emajdelmarkerr'] = 'Erreur lors de l\'effacement de la marque "%s" pour le groupe de tables "%s" !';

	// Marks before mark deletion
	$plugin_lang['emajdelmarks'] = 'E-Maj : Supprimer des marques';
	$plugin_lang['emajconfirmdelmarks'] = 'Etes-vous sûr de vouloir supprimer toutes les marques antérieures à la marque "%s" pour le groupe de tables "%s" ?';
	$plugin_lang['emajdelmarksok'] = 'Les (%s) marques antérieures à la marque "%s" ont été supprimées pour le groupe de tables "%s".';
	$plugin_lang['emajdelmarkserr'] = 'Erreur lors de la suppression des marques antérieures à la marque "%s" pour le groupe de tables "%s" !';
?>
