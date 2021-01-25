<?php
/**
 * e107 website system
 *
 * Copyright (C) 2008-2018 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */


class e107Test extends \Codeception\Test\Unit
{

	/** @var e107 */
	private $e107;

	protected function _before()
	{

		try
		{
			$this->e107 = e107::getInstance();
		}
		catch(Exception $e)
		{
			$this->fail("Couldn't load e107 object");
		}

	}

	public function testGetInstance()
	{

		//	$this->e107->getInstance();
		//$res = $this->e107::getInstance();
		//	$this->assertTrue($res);
	}

	public function testInitCore()
	{

		//$res = null;
		include_once(APP_PATH . '/e107_config.php'); // contains $E107_CONFIG = array('site_path' => '000000test');

		$e107_paths = @compact('ADMIN_DIRECTORY', 'FILES_DIRECTORY', 'IMAGES_DIRECTORY', 'THEMES_DIRECTORY', 'PLUGINS_DIRECTORY', 'HANDLERS_DIRECTORY', 'LANGUAGES_DIRECTORY', 'HELP_DIRECTORY', 'DOWNLOADS_DIRECTORY', 'UPLOADS_DIRECTORY', 'SYSTEM_DIRECTORY', 'MEDIA_DIRECTORY', 'CACHE_DIRECTORY', 'LOGS_DIRECTORY', 'CORE_DIRECTORY', 'WEB_DIRECTORY');
		$sql_info = @compact('mySQLserver', 'mySQLuser', 'mySQLpassword', 'mySQLdefaultdb', 'mySQLprefix', 'mySQLport');
		$res = $this->e107->initCore($e107_paths, e_ROOT, $sql_info, varset($E107_CONFIG, array()));

		$this->assertEquals('000000test', $res->site_path);

		$this->assertEquals('/', e_HTTP);

	}

	public function testRenderLayout()
	{

		$LAYOUT = file_get_contents(e_THEME . "bootstrap3/theme.html");
		ob_start();

		e107::renderLayout($LAYOUT);

		$result = ob_get_clean();

		$this->assertStringNotContainsString('{MENU=1}', $result);
		$this->assertStringNotContainsString('{NAVIGATION=main}', $result);
		$this->assertStringNotContainsString('{BOOTSTRAP_BRANDING}', $result);


	}

	/*
			public function testInitInstall()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testMakeSiteHash()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testSetDirs()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testPrepareDirs()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testDefaultDirs()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testInitInstallSql()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetRegistry()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testSetRegistry()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetFolder()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetE107()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testIsCli()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetMySQLConfig()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetSitePath()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetHandlerPath()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testAddHandler()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testIsHandler()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetHandlerOverload()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testSetHandlerOverload()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testIsHandlerOverloadable()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetSingleton()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetObject()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetConfig()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetPref()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testFindPref()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetPlugConfig()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetPlugLan()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetPlugPref()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testFindPlugPref()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetThemeConfig()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetThemePref()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testSetThemePref()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetThemeGlyphs()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetParser()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetScParser()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetSecureImg()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetScBatch()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetDb()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetCache()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetBB()
			{
				$res = null;
				$this->assertTrue($res);
			}*/


	public function testGetUserSession()
	{

		$tmp = e107::getUserSession();

		$className = get_class($tmp);

		$res = ($className === 'UserHandler');

		$this->assertTrue($res);

	}

	/**
	 * Test sessions and namespaced sessions.
	 * Make sure data is kept separate.
	 */
	public function testGetSession()
	{

		$e107 = $this->e107;

		// Simple session set/get
		$sess = $e107::getSession();
		$input = 'test-key-result';
		$sess->set('test-key', $input);
		$this->assertSame($input, $sess->get('test-key'));

		// Create Session 2 with namespace. Make sure Session 1 key is not present.
		$sess2 = $e107::getSession('other');
		$this->assertEmpty($sess2->get('test-key'));

		// Make sure Session 2 key is set and not present in Session 1.
		$sess2->set('other-key', true);
		$this->assertEmpty($sess->get('other-key'));
		$this->assertTrue($sess2->get('other-key'));

	}

	/*
			public function testGetRedirect()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetRate()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetSitelinks()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetRender()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetEmail()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetBulkEmail()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetEvent()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetArrayStorage()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetMenu()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetTheme()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetUrl()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetFile()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetForm()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetAdminLog()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetLog()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetDateConvert()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetDate()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetDebug()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetNotify()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetOverride()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetLanguage()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetIPHandler()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetXml()
			{
				$res = null;
				$this->assertTrue($res);
			}
			*/

	public function testGetHybridAuth()
	{

		$object = e107::getHybridAuth();
		$this->assertInstanceOf(Hybridauth\Hybridauth::class, $object);
	}

	/*
	public function testGetUserClass()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testGetSystemUser()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testUser()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testSerialize()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testUnserialize()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testGetUser()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testGetModel()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testGetUserStructure()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testGetUserExt()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testGetUserPerms()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testGetRank()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testGetPlugin()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testGetPlug()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testGetOnline()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testGetChart()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testGetComment()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testGetCustomFields()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testGetMedia()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testGetNav()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testGetMessage()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testGetAjax()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testGetLibrary()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testLibrary()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testGetJs()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testSet()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testJs()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testLink()
	{


	}

	public function testCss()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testDebug()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testGetJshelper()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testMeta()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testGetAdminUI()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testGetAddon()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testGetAddonConfig()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testCallMethod()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testGetUrlConfig()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testGetThemeInfo()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testCoreTemplatePath()
	{
		$res = null;
		$this->assertTrue($res);
	}

	public function testTemplatePath()
	{
		$res = null;
		$this->assertTrue($res);
	}
*/
	public function testLoadAdminIcons()
	{

		$e107 = $this->e107;

		$legacyList = array (
		  'E_16_FACEBOOK'              => '<img class=\'icon S16\' src=\'./e107_images/admin_images/facebook_16.png\' alt=\'\' />',
		  'E_16_TWITTER'               => '<img class=\'icon S16\' src=\'./e107_images/admin_images/twitter_16.png\' alt=\'\' />',
		  'E_16_GITHUB'                => '<img class=\'icon S16\' src=\'./e107_images/admin_images/github_16.png\' alt=\'\' />',
		  'E_16_E107'                  => '<img class=\'icon S16\' src=\'./e107_images/e107_icon_16.png\' alt=\'\' />',
		  'E_32_E107'                  => '<img class=\'icon S32\' src=\'./e107_images/e107_icon_32.png\' alt=\'\' />',
		  'E_32_ADMIN'                 => '<i class=\'S32 e-admins-32\'></i>',
		  'E_32_ADPASS'                => '<i class=\'S32 e-adminpass-32\'></i>',
		  'E_32_BANLIST'               => '<i class=\'S32 e-banlist-32\'></i>',
		  'E_32_CACHE'                 => '<i class=\'S32 e-cache-32\'></i> ',
		  'E_32_CREDITS'               => '<i class=\'S32 e-e107_icon-32.png\'></i>',
		  'E_32_CRON'                  => '<i class=\'S32 e-cron-32\'></i> ',
		  'E_32_CUST'                  => '<i class=\'S32 e-custom-32\'></i> ',
		  'E_32_DATAB'                 => '<i class=\'S32 e-database-32\'></i> ',
		  'E_32_DOCS'                  => '<i class=\'S32 e-docs-32\'></i> ',
		  'E_32_EMOTE'                 => '<i class=\'S32 e-emoticons-32\'></i> ',
		  'E_32_FILE'                  => '<i class=\'S32 e-filemanager-32\'></i> ',
		  'E_32_FORUM'                 => '<i class=\'S32 e-forums-32\'></i> ',
		  'E_32_FRONT'                 => '<i class=\'S32 e-frontpage-32\'></i> ',
		  'E_32_IMAGES'                => '<i class=\'S32 e-images-32\'></i> ',
		  'E_32_INSPECT'               => '<i class=\'S32 e-fileinspector-32\'></i> ',
		  'E_32_LINKS'                 => '<i class=\'S32 e-links-32\'></i> ',
		  'E_32_WELCOME'               => '<i class=\'S32 e-welcome-32\'></i> ',
		  'E_32_MAIL'                  => '<i class=\'S32 e-mail-32\'></i> ',
		  'E_32_MAINTAIN'              => '<i class=\'S32 e-maintain-32\'></i> ',
		  'E_32_MENUS'                 => '<i class=\'S32 e-menus-32\'></i> ',
		  'E_32_META'                  => '<i class=\'S32 e-meta-32\'></i> ',
		  'E_32_NEWS'                  => '<i class=\'S32 e-news-32\'></i> ',
		  'E_32_NEWSFEED'              => '<i class=\'S32 e-newsfeeds-32\'></i> ',
		  'E_32_NOTIFY'                => '<i class=\'S32 e-notify-32\'></i> ',
		  'E_32_PHP'                   => '<i class=\'S32 e-phpinfo-32\'></i> ',
		  'E_32_POLLS'                 => '<i class=\'S32 e-polls-32\'></i> ',
		  'E_32_PREFS'                 => '<i class=\'S32 e-prefs-32\'></i> ',
		  'E_32_SEARCH'                => '<i class=\'S32 e-search-32\'></i> ',
		  'E_32_UPLOADS'               => '<i class=\'S32 e-uploads-32\'></i> ',
		  'E_32_EURL'                  => '<i class=\'S32 e-eurl-32\'></i> ',
		  'E_32_USER'                  => '<i class=\'S32 e-users-32\'></i> ',
		  'E_32_USER_EXTENDED'         => '<i class=\'S32 e-extended-32\'></i> ',
		  'E_32_USERCLASS'             => '<i class=\'S32 e-userclass-32\'></i> ',
		  'E_32_LANGUAGE'              => '<i class=\'S32 e-language-32\'></i> ',
		  'E_32_PLUGIN'                => '<i class=\'S32 e-plugins-32\'></i> ',
		  'E_32_PLUGMANAGER'           => '<i class=\'S32 e-plugmanager-32\'></i> ',
		  'E_32_MAIN'                  => '<i class=\'S32 e-main-32\'></i> ',
		  'E_32_THEMEMANAGER'          => '<i class=\'S32 e-themes-32\'></i> ',
		  'E_32_COMMENT'               => '<i class=\'S32 e-comments-32\'></i> ',
		  'E_32_ADMINLOG'              => '<i class=\'S32 e-adminlogs-32\'></i> ',
		  'E_32_LOGOUT'                => '<i class=\'S32 e-logout-32\'></i> ',
		  'E_32_MANAGE'                => '<i class=\'S32 e-manage-32\'></i> ',
		  'E_32_CREATE'                => '<i class=\'S32 e-add-32\'></i> ',
		  'E_32_SETTINGS'              => '<i class=\'S32 e-settings-32\'></i> ',
		  'E_32_SYSINFO'               => '<i class=\'S32 e-sysinfo-32\'></i> ',
		  'E_32_CAT_SETT'              => '<i class=\'S32 e-cat_settings-32\'></i> ',
		  'E_32_CAT_USER'              => '<i class=\'S32 e-cat_users-32\'></i> ',
		  'E_32_CAT_CONT'              => '<i class=\'S32 e-cat_content-32\'></i> ',
		  'E_32_CAT_FILE'              => '<i class=\'S32 e-cat_files-32\'></i> ',
		  'E_32_CAT_TOOL'              => '<i class=\'S32 e-cat_tools-32\'></i> ',
		  'E_32_CAT_PLUG'              => '<i class=\'S32 e-cat_plugins-32\'></i> ',
		  'E_32_CAT_MANAGE'            => '<i class=\'S32 e-manage-32\'></i> ',
		  'E_32_CAT_MISC'              => '<i class=\'S32 e-settings-32\'></i> ',
		  'E_32_CAT_ABOUT'             => '<i class=\'S32 e-info-32\'></i> ',
		  'E_32_NAV_MAIN'              => '<i class=\'S32 e-main-32\'></i> ',
		  'E_32_NAV_DOCS'              => '<i class=\'S32 e-docs-32\'></i> ',
		  'E_32_NAV_LEAV'              => '<i class=\'S32 e-leave-32\'></i> ',
		  'E_32_NAV_LGOT'              => '<i class=\'S32 e-logout-32\'></i> ',
		  'E_32_NAV_ARROW'             => '<i class=\'S32 e-arrow-32\'></i> ',
		  'E_32_NAV_ARROW_OVER'        => '<i class=\'S32 e-arrow_over-32\'></i> ',
		  'E_16_ADMIN'                 => '<i class=\'S16 e-admins-16\'></i>',
		  'E_16_ADPASS'                => '<i class=\'S16 e-adminpass-16\'></i>',
		  'E_16_BANLIST'               => '<i class=\'S16 e-banlist-16\'></i>',
		  'E_16_CACHE'                 => '<i class=\'S16 e-cache-16\'></i>',
		  'E_16_COMMENT'               => '<i class=\'S16 e-comments-16\'></i>',
		  'E_16_CREDITS'               => '<i class=\'S16 e-e107_icon-16\'></i>',
		  'E_16_CRON'                  => '<i class=\'S16 e-cron-16\'></i>',
		  'E_16_CUST'                  => '<i class=\'S16 e-custom-16\'></i>',
		  'E_16_CUSTOMFIELD'           => '<i class=\'S16 e-custom_field-16\'></i>',
		  'E_16_DATAB'                 => '<i class=\'S16 e-database-16\'></i>',
		  'E_16_DOCS'                  => '<i class=\'S16 e-docs-16\'></i>',
		  'E_16_EMOTE'                 => '<i class=\'S16 e-emoticons-16\'></i>',
		  'E_16_FILE'                  => '<i class=\'S16 e-filemanager-16\'></i>',
		  'E_16_FORUM'                 => '<i class=\'S16 e-forums-16\'></i>',
		  'E_16_FRONT'                 => '<i class=\'S16 e-frontpage-16\'></i>',
		  'E_16_IMAGES'                => '<i class=\'S16 e-images-16\'></i>',
		  'E_16_INSPECT'               => '<i class=\'S16 e-fileinspector-16\'></i>',
		  'E_16_LINKS'                 => '<i class=\'S16 e-links-16\'></i>',
		  'E_16_WELCOME'               => '<i class=\'S16 e-welcome-16\'></i>',
		  'E_16_MAIL'                  => '<i class=\'S16 e-mail-16\'></i>',
		  'E_16_MAINTAIN'              => '<i class=\'S16 e-maintain-16\'></i>',
		  'E_16_MENUS'                 => '<i class=\'icon S16 e-menus-16\'></i>',
		  'E_16_META'                  => '<i class=\'icon S16 e-meta-16\'></i>',
		  'E_16_NEWS'                  => '<i class=\'icon S16 e-news-16\'></i>',
		  'E_16_NEWSFEED'              => '<i class=\'S16 e-newsfeeds-16\'></i>',
		  'E_16_NOTIFY'                => '<i class=\'S16 e-notify-16\'></i>',
		  'E_16_PHP'                   => '<i class=\'S16 e-phpinfo-16\'></i>',
		  'E_16_POLLS'                 => '<i class=\'S16 e-polls-16\'></i>',
		  'E_16_PREFS'                 => '<i class=\'S16 e-prefs-16\'></i>',
		  'E_16_SEARCH'                => '<i class=\'S16 e-search-16\'></i>',
		  'E_16_UPLOADS'               => '<i class=\'S16 e-uploads-16\'></i>',
		  'E_16_EURL'                  => '<i class=\'S16 e-eurl-16\'></i>',
		  'E_16_USER'                  => '<i class=\'S16 e-users-16\'></i>',
		  'E_16_USER_EXTENDED'         => '<i class=\'S16 e-extended-16\'></i>',
		  'E_16_USERCLASS'             => '<i class=\'S16 e-userclass-16\'></i>',
		  'E_16_LANGUAGE'              => '<i class=\'S16 e-language-16\'></i>',
		  'E_16_PLUGIN'                => '<i class=\'S16 e-plugins-16\'></i>',
		  'E_16_PLUGMANAGER'           => '<i class=\'S16 e-plugmanager-16\'></i>',
		  'E_16_THEMEMANAGER'          => '<i class=\'S16 e-themes-16\'></i>',
		  'E_16_ADMINLOG'              => '<i class=\'S16 e-adminlogs-16\'></i>',
		  'E_16_MANAGE'                => '<i class=\'S16 e-manage-16\'></i>',
		  'E_16_CREATE'                => '<i class=\'S16 e-add-16\'></i>',
		  'E_16_SETTINGS'              => '<i class=\'S16 e-settings-16\'></i>',
		  'E_16_SYSINFO'               => '<i class=\'S16 e-sysinfo-16\'></i>',
		  'E_16_FAILEDLOGIN'           => '<i class=\'S16 e-failedlogin-16\'></i>',
		  'E_32_TRUE'                  => '<i class=\'S32 e-true-32\'></i>',
		  'ADMIN_CHILD_ICON'           => '<img src="/e107_images/generic/branchbottom.gif" class="treeprefix level-x icon" alt="" />',
		  'ADMIN_FILTER_ICON'          => '<i class=\'fa fa-filter\'></i>',
		  'ADMIN_TRUE_ICON'            => '<span class=\'text-success admin-true-icon\'>&#10004;</span>',
		  'ADMIN_FALSE_ICON'           => '<span class=\'text-danger admin-false-icon\'>&#10799;</span>',
		  'ADMIN_WARNING_ICON'         => '<i class=\'fa fa-warning text-warning\'></i>',
		  'ADMIN_GRID_ICON'            => '<i class=\'fa fa-th\'></i>',
		  'ADMIN_LIST_ICON'            => '<i class=\'fa fa-th-list\'></i>',
		  'ADMIN_EDIT_ICON'            => '<i class=\'S32 e-edit-32\'></i>',
		  'ADMIN_DELETE_ICON'          => '<i class=\'S32 e-delete-32\'></i>',
		  'ADMIN_SORT_ICON'            => '<i class=\'S32 e-sort-32\'></i>',
		  'ADMIN_EXECUTE_ICON'         => '<i class=\'S32 e-execute-32\'></i>',
		  'ADMIN_PAGES_ICON'           => '<i class=\'S32 e-custom-32\'></i>',
		  'ADMIN_ADD_ICON'             => '<i class=\'S32 e-add-32\'></i>',
		  'ADMIN_INFO_ICON'            => '<i class=\'fa fa-question-circle\'></i>',
		  'ADMIN_CONFIGURE_ICON'       => '<i class=\'S32 e-settings-32\'></i>',
		  'ADMIN_VIEW_ICON'            => '<i class=\'S32 e-search-32\'></i>',
		  'ADMIN_URL_ICON'             => '<i class=\'S16 e-forums-16\'></i>',
		  'ADMIN_INSTALLPLUGIN_ICON'   => '<i class=\'S32 e-plugin_install-32\'></i>',
		  'ADMIN_UNINSTALLPLUGIN_ICON' => '<i class=\'S32 e-plugin_uninstall-32\'></i>',
		  'ADMIN_UPGRADEPLUGIN_ICON'   => '<i class=\'S32 e-up-32\'></i>',
		  'ADMIN_REPAIRPLUGIN_ICON'    => '<i class=\'S32 e-configure-32\'></i>',
		  'ADMIN_UP_ICON'              => '<i class=\'S32 e-up-32\'></i>',
		  'ADMIN_DOWN_ICON'            => '<i class=\'S32 e-down-32\'></i>',
		  'ADMIN_EDIT_ICON_PATH'       => '/e107_images/admin_images/edit_32.png',
		  'ADMIN_DELETE_ICON_PATH'     => '/e107_images/admin_images/delete_32.png',
		  'ADMIN_WARNING_ICON_PATH'    => '/e107_images/admin_images/warning_32.png',
		  'E_24_PLUGIN'                => "<i class='S24 e-plugins-24'></i> ",
		  'ADMIN_FALSE_ICON'           => "<span class='text-danger admin-false-icon'>&#10799;</span>"

		);


		$new = $e107::loadAdminIcons();

		foreach($new as $key=>$val)
		{
			if(!isset($legacyList[$key]))
			{
				$this->fail("Remove {$key} FROM admin_icons_template");
			}

			$this->assertSame($legacyList[$key], $val, $key." should equal: ".$legacyList[$key]);
		}

		foreach($legacyList as $key=>$val)
		{
			if(!isset($new[$key]))
			{
				$this->fail("{$key} is missing from admin_icons_template");
			}

		}

		$template2 = $e107::loadAdminIcons();

		$this->assertSame($new, $template2);
	}


	public function testGetCoreTemplate()
	{
		$e107 = $this->e107;
		$templates = scandir(e_CORE . "templates");


		$exclude = array(
		//	'admin_icons_template.php',
			'admin_template.php',// FIXME - convert the template to v2.x standards.
			'bbcode_template.php',
			'online_template.php', // FIXME - convert the template to v2.x standards.
			'sitedown_template.php', // FIXME - convert the template to v2.x standards.
		);

		foreach($templates as $file)
		{
			if(strpos($file, '_template.php') === false || in_array($file, $exclude))
			{
				continue;
			}

			$path = str_replace('_template.php', '', $file);

			e107::coreLan($path);

			if($path === 'signup')
			{
				e107::coreLan('user');
			}

			$result = $e107::getCoreTemplate($path);

			$this->assertIsArray($result, $path . "  template was not an array");
			$this->assertNotEmpty($result, $path . " template was empty");

		}

		//$res = null;
		//$this->assertTrue($res);
	}
/*
	private function clearRelatedRegistry($type)
	{
		$registry = e107::getRegistry('_all_');

		$result = [];
		foreach($registry as $reg => $v)
		{
			if(strpos($reg, $type) !== false)
			{
				e107::setRegistry($reg);
				$result[] = $reg;
			}

		}

		sort($result);

		return $result;
	}*/
/*
	public function testGetTemplatePluginThemeMatch()
	{
		e107::plugLan('download', 'front', true);

		e107::getConfig()->set('sitetheme', 'bootstrap3');
		$template = e107::getTemplate('download', null, null);
		var_export($template['header']);
		echo "\n\n";


		e107::getConfig()->set('sitetheme', '_blank');
		$template = e107::getTemplate('download', null, null);
		var_export($template['header']);
		echo "\n\n";

		e107::getConfig()->set('sitetheme', 'bootstrap3'); // doesn't have a download template, so fallback.
		$template = e107::getTemplate('download', null, null); // theme override is enabled by default.
		var_export($template['header']);
		echo "\n\n";

		e107::getConfig()->set('sitetheme', 'bootstrap3');
	}
*/

	public function testGetTemplateOverride()
	{
		// Loads e107_themes/bootstrap3/templates/gallery/gallery_template.php
		$template = e107::getTemplate('gallery', null, null, true, false); // true & false default, loads theme (override true)
		$this->assertEquals("My Gallery", $template['list']['caption']);

		// Duplicate to load registry
		$template2 = e107::getTemplate('gallery', null, null, true, false); // true & false default, loads theme (override true)
		$this->assertEquals("My Gallery", $template2['list']['caption']);

		$this->assertSame($template, $template2);

	}


	public function testGetTemplateOverrideMerge()
	{
		// Loads e107_plugins/gallery/templates/gallery_template.php then overwrites it with e107_themes/bootstrap3/templates/gallery/gallery_template.php
		$template = e107::getTemplate('gallery', null, null, true, true); // theme override is enabled, and theme merge is enabled.
		$this->assertArrayHasKey('merged-example', $template);
		$this->assertEquals("My Gallery", $template['list']['caption']); // ie. from the original
		$this->assertNotEmpty($template['merged-example']);

		// duplicate to load registry
		$template2 = e107::getTemplate('gallery', null, null, true, true); // theme override is enabled, and theme merge is enabled.
		$this->assertArrayHasKey('merged-example', $template2);
		$this->assertEquals("My Gallery", $template2['list']['caption']); // ie. from the original
		$this->assertNotEmpty($template2['merged-example']);

		$this->assertSame($template, $template2);

	}

	public function testGetTemplateMerge()
	{

		// // ie. should be from plugin template, not theme.
		$template = e107::getTemplate('gallery', null, null, false, true); // theme override is disabled, theme merge is enabled.
		$this->assertEquals("Gallery", $template['list']['caption']);
		$this->assertArrayNotHasKey('merged-example', $template);

		// duplicate to load registry.
		$template2 = e107::getTemplate('gallery', null, null, false, true); // theme override is disabled, theme merge is enabled.
		$this->assertEquals("Gallery", $template2['list']['caption']);
		$this->assertArrayNotHasKey('merged-example', $template2);

		$this->assertSame($template, $template2);

	}

	/**
	 * This test checks getTemplate() with no merging or override.
	 */
	public function testGetTemplate()
	{
		// Loads e107_plugins/gallery/templates/gallery_template.php
		$template = e107::getTemplate('gallery', null, null, false, false); // theme override is disabled.
		$this->assertEquals("Gallery", $template['list']['caption']);

		// Duplicate to load registry.
		$template2 = e107::getTemplate('gallery', null, null, false, false); // theme override is disabled.
		$this->assertEquals("Gallery", $template2['list']['caption']);

		$this->assertSame($template, $template2);
	}

	/*
			public function testTemplateWrapper()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testScStyle()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetTemplateInfo()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetLayouts()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function test_getTemplate()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testIncludeLan()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testCoreLan()
			{
				$res = null;
				$this->assertTrue($res);
			}
*/
	public function testPlugLan()
	{

		$e107 = $this->e107;

		$tests = array(
					// plug, param 1, param 2, expected
			0   => array('banner', '', false, 'e107_plugins/banner/languages/English_front.php'),
			1   => array('forum', 'front', true, 'e107_plugins/forum/languages/English/English_front.php'),
			2   => array('gallery', true, true, 'e107_plugins/gallery/languages/English/English_admin.php'),
			3   => array('forum', 'menu', true, 'e107_plugins/forum/languages/English/English_menu.php'),
			4   => array('banner', true, false, 'e107_plugins/banner/languages/English_admin.php'),
			5   => array('chatbox_menu', e_LANGUAGE, false, 'e107_plugins/chatbox_menu/languages/English/English.php'),
			6   => array('comment_menu', null, false, 'e107_plugins/comment_menu/languages/English.php'),
			7   => array('poll', null, false, 'e107_plugins/poll/languages/English.php'),
			8   => array('poll', null, false, 'e107_plugins/poll/languages/English.php'),
		);

		foreach($tests as $plug=>$var)
		{
			$result = $e107::plugLan($var[0], $var[1], $var[2], true);
			if(!isset($var[3]))
			{
				echo $result."\n";
				continue;
			}

			$this->assertStringContainsString($var[3], $result);
			$e107::plugLan($var[0], $var[1], $var[2]);
		}
/*
		$registry = $e107::getRegistry('_all_');

		foreach($registry as $k=>$v)
		{
			if(strpos($k, 'core/e107/pluglan/') !== false)
			{
				echo $k."\n";

			}


		}*/






	}

	/*
				public function testThemeLan()
				{
					$res = null;
					$this->assertTrue($res);
				}

				public function testLan()
				{
					$res = null;
					$this->assertTrue($res);
				}

				public function testPref()
				{
					$res = null;
					$this->assertTrue($res);
				}
		*/


	private function generateRows($var, $plugin)
	{

		preg_match_all('#\{([a-z_]*)\}#', $var['sef'], $matches);


		$variables = array('-one-', '-two-', '-three-');
		$ret = [];

		if(!empty($matches[1]))
		{


			$c = 0;
			foreach($matches[1] as $v)
			{
				if($v === 'alias' && !empty($var['alias']))
				{
					$ret['alias'] = $var['alias'];
				}
				else
				{
					$ret[$v] = $variables[$c];
					$c++;
				}

			}

		}

		/*else
		{
			echo "\n".$plugin.' had no matches for: '.varset($var['sef'])."\n";
		}*/

		return $ret;

	}

	private function generateExpected($string, $rows)
	{

		$search = array('&');;
		$replace = array('&amp;');

		foreach($rows as $k => $v)
		{
			$search[] = '{' . $k . '}';
			$replace[] = $v;

		}

		return SITEURL . str_replace($search, $replace, $string);

	}

	public function testCanonical()
	{
		$e107 = $this->e107;
		$e107::canonical('_RESET_');
		$e107::canonical('news');

		$result = $e107::canonical();
		$this->assertSame("https://localhost/e107/news", $result);

	}


	public function testUrl()
	{

		$obj = $this->e107;

		// Test FULL url option on Legacy url with new options['mode']
		$tests = array(
			0 => array(
				'plugin'     => 'news/view/item',
				'key'        => array('news_id' => 1, 'news_sef' => 'my-news-item', 'category_sef' => 'my-category'),
				'row'        => array(),
				'options'    => ['mode' => 'full'],
			),
			1 => array(
				'plugin'     => 'news/view/item',
				'key'        => array('news_id' => 1, 'news_sef' => 'my-news-item', 'category_sef' => 'my-category'),
				'row'        => 'full=1&encode=0',
				'options'    => ['mode' => 'full'],
			),
			2 => array(
				'plugin'     => 'news/view/item',
				'key'        => array('news_id' => 1, 'news_sef' => 'my-news-item', 'category_sef' => 'my-category'),
				'row'        => '',
				'options'    => ['mode' => 'full'],
			),
			3 => array(
				'plugin'     => 'news/view/item',
				'key'        => array('news_id' => 1, 'news_sef' => 'my-news-item', 'category_sef' => 'my-category'),
				'row'        => null,
				'options'    => ['mode' => 'full'],
			),

		);
		foreach($tests as $v)
		{
			$result = $obj::url($v['plugin'], $v['key'], $v['row'], $v['options']);
			$this->assertStringContainsString('http', $result);
		}



		$tests = array();

		$all = e107::getAddonConfig('e_url');
		foreach($all as $plugin => $var)
		{
			if($plugin === 'gallery' || $plugin === 'rss_menu') // fixme - sef may be enabled or disabled each time tests are run
			{
				continue;
			}

			foreach($var as $key => $value)
			{
				$rows = $this->generateRows($value, $plugin);
				$tests[] = array(
					'plugin'     => $plugin,
					'key'        => $key,
					'row'        => $rows,
					'options'    => ['mode' => 'full'],
					'_expected_' => $this->generateExpected($value['sef'], $rows),

				);
			}

		}


		foreach($tests as $index => $var)
		{
			if(empty($var['plugin']))
			{
				continue;
			}

			$result = $obj::url($var['plugin'], $var['key'], $var['row'], $var['options']);

			if(empty($var['_expected_']))
			{
				echo $result . "\n";
				continue;
			}
			$this->assertEquals($var['_expected_'], $result);
			//	$this->assertEquals("https://localhost/e107/news", $result);
		}


	}

	/**
	 *        /*
	 * e107::getUrl()->create('page/book/index', $row,'allow=chapter_id,chapter_sef,book_sef') ;
	 * e107::getUrl()->create('user/profile/view', $this->news_item)
	 * e107::getUrl()->create('user/profile/view', array('name' => $this->var['user_name'], 'id' => $this->var['user_id']));
	 * e107::getUrl()->create('page/chapter/index', $row,'allow=chapter_id,chapter_sef,book_sef') ;
	 * e107::getUrl()->create('user/myprofile/edit');
	 * e107::getUrl()->create('gallery/index/list', $this->var);
	 * e107::getUrl()->create('news/view/item', $row, array('full' => 1));
	 * e107::getUrl()->create('news/list/all'),
	 * e107::getUrl()->create('page/view/index',$row),
	 * e107::getUrl()->create('page/chapter/index', $sef),
	 * ($sef = $row;
	 * $sef['chapter_sef'] = $this->getSef($row['chapter_id']);
	 * $sef['book_sef']    = $this->getSef($row['chapter_parent']);)
	 *
	 * e107::getUrl()->create('news/list/tag', array('tag' => $word));
	 * $LINKTOFORUM = e107::getUrl()->create('forum/forum/view', array('id' => $row['thread_forum_id'])); //$e107->url->getUrl('forum', 'forum', "func=view&id={$row['thread_forum_id']}");
	 * e107::getUrl()->create('search');
	 */
	public function testUrlLegacy()
	{

		// set eURL config to 'Friendly'
		$oldConfig = e107::getPref('url_config');

		$newConfig = array(
			'news'    => 'core/sef_full',
			'page'    => 'core/sef_chapters',
			'search'  => 'core/rewrite',
			'system'  => 'core/rewrite',
			'user'    => 'core/rewrite',
	//		'gallery' => 'plugin/rewrite'
		);


		$this->setUrlConfig($newConfig);

		$legacyTests = array(

			0 => array(
				'route'      => 'news/view/item',
				'row'        => array('news_id' => 1, 'news_sef' => 'my-news-item', 'category_sef' => 'my-category'),
				'options'    => 'full=1',
				'_expected_' => 'https://localhost/e107/news/my-category/my-news-item'
			),
			1 => array(
				'route'      => 'news/view/item',
				'row'        => array('id' => 1, 'name' => 'my-news-item', 'category' => 'my-category'),
				'options'    => 'full=1',
				'_expected_' => 'https://localhost/e107/news/my-category/my-news-item'
			),
			2 => array(
				'route'      => 'news/list/short',
				'row'        => array('id' => 1, 'name' => 'my-news-item', 'category' => 'my-category'),
				'options'    => 'full=1',
				'_expected_' => 'https://localhost/e107/news/short/my-news-item'
			),
			3 => array(
				'route'      => 'news/list/tag',
				'row'        => array('tag' => 'myword'),
				'options'    => 'full=1',
				'_expected_' => 'https://localhost/e107/news/tag/myword'
			),
			4 => array(
				'route'      => 'search',
				'row'        => '',
				'options'    => 'full=1',
				'_expected_' => 'https://localhost/e107/search'
			),
			5 => array(
				'route'      => 'user/profile/view',
				'row'        => array('user_id' => 3, 'user_name' => 'john'),
				'options'    => 'full=1',
				'_expected_' => 'https://localhost/e107/user/john'
			),
			6 => array(
				'route'      => 'page/book/index',
				'row'        => array('chapter_id' => 2, 'chapter_sef' => 'my-book'),
				'options'    => 'full=1',
				'_expected_' => 'https://localhost/e107/page/my-book'
			),
			7 => array(
				'route'      => 'page/chapter/index',
				'row'        => array('chapter_id' => 2, 'chapter_sef' => 'my-chapter', 'book_sef' => 'my-book'),
				'options'    => 'full=1',
				'_expected_' => 'https://localhost/e107/page/my-book/my-chapter'
			),
			8 => array(
				'route'      => 'page/view',
				'row'        => array('page_id' => 3, 'page_sef' => 'my-page', 'chapter_id' => 2, 'chapter_sef' => 'my-chapter', 'book_sef' => 'my-book'),
				'options'    => 'full=1',
				'_expected_' => 'https://localhost/e107/page/my-book/my-chapter/my-page'
			),


			// todo add more.
		);

		$e107 = $this->e107;

		foreach($legacyTests as $index => $var)
		{
			if(empty($var['route']))
			{
				continue;
			}

			$result = $e107::url($var['route'], $var['row'], $var['options']);
			$lresult = e107::getUrl()->create($var['route'], $var['row'], $var['options']);

			if(empty($var['_expected_']))
			{
				echo $result . "\n";
				echo $lresult . "\n\n";
				continue;
			}

			$this->assertEquals($result, $lresult, "Legacy Test #" . $index . " -- e107::getUrl()->create('" . $var['route'] . "') didn't match e107::url('" . $var['route'] . "')");
			$this->assertEquals($var['_expected_'], $result, 'Legacy URL index #' . $index . ' failed');


		}


		$this->setUrlConfig($oldConfig);  // return config to previous state.


	}


	/**
	 * Save the url_config preference
	 * @param array $newConfig
	 */
	private function setUrlConfig($newConfig = array())
	{

		if(empty($newConfig))
		{
			return null;
		}

		$cfg = e107::getConfig();

		foreach($newConfig as $k => $v)
		{
			$cfg->setPref('url_config/' . $k, $v);
		}

		$cfg->save(false, true);

		/** @var eRouter $router */
		$router = e107::getUrl()->router(); // e107::getSingleton('eRouter');
		$rules = $router->getRuleSets();

		if(empty($rules['news']) || empty($rules['page']))
		{
			$router->loadConfig(true);
		}

	}

	/**
	 * @see https://github.com/e107inc/e107/issues/4054
	 */
	public function testUrlOptionQueryHasCompliantAmpersand()
	{

		$e107 = $this->e107;
		$e107::getPlugin()->install('forum');
		$url = $e107::url('forum', 'topic', [], array(
			'query' => array(
				'f'  => 'post',
				'id' => 123
			),
		));
		$this->assertEquals(
			e_PLUGIN_ABS . 'forum/forum_viewtopic.php?f=post&amp;id=123',
			$url, "Generated href does not match expectation"
		);

	}

	public function testUrlOptionQueryUrlEncoded()
	{

		$e107 = $this->e107;
		$e107::getPlugin()->install('forum');
		$url = $e107::url('forum', 'post', [], array(
			'query' => array(
				"didn't" => '<tag attr="such wow"></tag>',
				'did'    => 'much doge',
			),
		));
		$this->assertEquals(
			e_HTTP .
			'forum/post/?didn%27t=%3Ctag%20attr%3D%22such%20wow%22%3E%3C/tag%3E&amp;did=much%20doge',
			$url, "Generated href query string did not have expected URL encoding"
		);

	}

	public function testUrlEscapesHtmlSpecialChars()
	{

		$e107 = $this->e107;
		$e107::getPlugin()->install('forum');
		$url = $e107::url('forum', 'forum', [
			'forum_sef' => '<>',
		], array(
			'fragment' => 'Arts & Crafts <tag attr="can\'t inject here"></tag>'
		));
		$this->assertEquals(
			e_HTTP .
			'forum/&lt;&gt;/#Arts &amp; Crafts &lt;tag attr=&quot;can&#039;t inject here&quot;&gt;&lt;/tag&gt;',
			$url, "Generated href did not prevent HTML tag injection as expected"
		);

	}

	/*
			public function testRedirect()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetError()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testHttpBuildQuery()
			{
				$res = null;
				$this->assertTrue($res);
			}
*/
	public function testMinify()
	{

		$text = "something ; other or ; else";
		$expected = "something;other or;else";

		$result = e107::minify($text);

		$this->assertEquals($expected, $result);

	}

	public function testWysiwyg()
	{

		e107::getConfig()->setPref('wysiwyg', true)->save();
		$tinyMceInstalled = e107::isInstalled('tinymce4');

		$tests = array(
			//input     => expected
			'default'  => ($tinyMceInstalled) ? 'tinymce4' : 'bbcode',
			'bbcode'   => 'bbcode',
			'tinymce4' => ($tinyMceInstalled) ? 'tinymce4' : 'bbcode',
		);

		foreach($tests as $input => $expected)
		{
			e107::wysiwyg($input);     // set the wysiwyg editor.
			$result = e107::wysiwyg(null, true);  // get the name of the editor.
			$this->assertSame($expected, $result, "Input: " . $input);
		}


		e107::getConfig()->setPref('wysiwyg', false)->save();  // wysiwyg is disabled.
		e107::wysiwyg('default');    // set as default.
		$result = e107::wysiwyg(null, true);   // get the editor value.
		$expected = 'bbcode';
		e107::getConfig()->setPref('wysiwyg', true)->save(); // enabled wysiwyg again.
		$this->assertSame($expected, $result);


	}

	/*
				public function testLoadLanFiles()
				{
					$res = null;
					$this->assertTrue($res);
				}

				public function testPrepare_request()
				{
					$res = null;
					$this->assertTrue($res);
				}
		*/

	public function testBase64DecodeOnAjaxURL()
	{

		$query = "mode=main&iframe=1&action=info&src=aWQ9ODgzJnVybD1odHRwcyUzQSUyRiUyRmUxMDcub3JnJTJGZTEwN19wbHVnaW5zJTJGYWRkb25zJTJGYWRkb25zLnBocCUzRmlkJTNEODgzJTI2YW1wJTNCbW9kYWwlM0QxJm1vZGU9YWRkb24mcHJpY2U9";

		$result = base64_decode($query, true);

		$this->assertFalse($result); // correct result is 'false'.
	}


	public function testInAdminDir()
	{
		return null; // FIXME
		$this->markTestSkipped("Skipped until admin-area conflict can be resolved."); // FIXME
		$tests = array(
			0  => array('path' => 'thumb.php', 'plugdir' => false, 'expected' => false),
			1  => array('path' => 'index.php', 'plugdir' => false, 'expected' => false),
			2  => array('path' => 'e107_admin/prefs.php', 'plugdir' => false, 'expected' => true),
			3  => array('path' => 'e107_admin/menus.php', 'plugdir' => false, 'expected' => true),
			4  => array('path' => 'e107_plugins/forum/forum.php', 'plugdir' => true, 'expected' => false),
			5  => array('path' => 'e107_plugins/vstore/admin_config.php', 'plugdir' => true, 'expected' => true),
			6  => array('path' => 'e107_plugins/login_menu/config.php', 'plugdir' => true, 'expected' => true),
			7  => array('path' => 'e107_plugins/myplugin/prefs.php', 'plugdir' => true, 'expected' => true),
			8  => array('path' => 'e107_plugins/dtree_menu/dtree_config.php', 'plugdir' => true, 'expected' => true),
			9  => array('path' => 'e107_plugins/myplugin/admin/something.php', 'plugdir' => true, 'expected' => true),
			10 => array('path' => 'e107_plugins/myplugin/bla_admin.php', 'plugdir' => true, 'expected' => true),
			11 => array('path' => 'e107_plugins/myplugin/admin_xxx.php', 'plugdir' => true, 'expected' => true),
		);

		foreach($tests as $index => $var)
		{
			$curPage = basename($var['path']);
			$result = $this->e107->inAdminDir($var['path'], $curPage, $var['plugdir']);
			$this->assertSame($var['expected'], $result, "Failed on index #" . $index);
		}

		// Test legacy override.
		$GLOBALS['eplug_admin'] = true;
		$result = $this->e107->inAdminDir('myplugin.php', 'myplugin.php', true);
		$this->assertTrue($result, "Legacy Override Failed");

		// Test legacy off.
		$GLOBALS['eplug_admin'] = false;
		$result = $this->e107->inAdminDir('myplugin.php', 'myplugin.php', true);
		$this->assertFalse($result);
	}


	public function testFilter_request()
	{

		//	define('e_DEBUG', true);
		//	$_SERVER['QUEST_STRING'] = "mode=main&iframe=1&action=info&src=aWQ9ODgzJnVybD1odHRwcyUzQSUyRiUyRmUxMDcub3JnJTJGZTEwN19wbHVnaW5zJTJGYWRkb25zJTJGYWRkb25zLnBocCUzRmlkJTNEODgzJTI2YW1wJTNCbW9kYWwlM0QxJm1vZGU9YWRkb24mcHJpY2U9";

		//$result = $this->e107::filter_request($test,'QUERY_STRING','_SERVER');

		//	$this->e107->prepare_request();

		//	var_dump($_SERVER['QUEST_STRING']);


		// 	$res = null;
		// $this->assertTrue($res);
	}

	/*
			public function testSet_base_path()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testSet_constants()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGet_override_rel()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGet_override_http()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testSet_paths()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testFix_windows_paths()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testSet_urls()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testSet_urls_deferred()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testSet_request()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testCanCache()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testIsSecure()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGetip()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testIpEncode()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testIpdecode()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testGet_host_name()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testParseMemorySize()
			{
				$res = null;
				$this->assertTrue($res);
			}
	*/
	public function testIsInstalled()
	{

		$obj = $this->e107;

		$result = $obj::isInstalled('user');

		// var_dump($result);
		$this->assertTrue($result);

		$result = $obj::isInstalled('news');

		// var_dump($result);
		$this->assertTrue($result);
	}


	public function testIsCompatible()
	{
		// version => expected
		$tests = array (
			'1'     => false, // assumed incompatible.
			'1.2.3' => false,
			'1.2'   => false,
			'2'     => true, // assumed to work with all versions from 2+
			'2.0'   => true,  // assumed to work with all versions from 2+
			'2.3'   => true,  // assumed to work with all versions from 2.3 onward.
			'2.1.0' => true,
			'2.2.0' => true,
			'2.3.0' => true,
			'2.3.1' => true,
			'1.7b'  => false,
			'2.9'   => false,
			'2.9.2' => false,
			'3'     => false,
		);

		$e107 = $this->e107;
	//	$ret = [];
		foreach($tests as $input=>$expected)
		{
			$result = $e107::isCompatible($input);
			$this->assertSame($expected, $result);
		//	$ret[$input] = $result;
		}



	}
	/*
			public function testIni_set()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testAutoload_register()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testAutoload()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function test__get()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testDestruct()
			{
				$res = null;
				$this->assertTrue($res);
			}

			public function testCoreUpdateAvailable()
			{
				$res = null;
				$this->assertTrue($res);
			}


	*/
}
