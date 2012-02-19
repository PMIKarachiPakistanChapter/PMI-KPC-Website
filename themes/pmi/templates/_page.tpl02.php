<?php
/**
 * @file
 * Zen theme's implementation to display a single Drupal page.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/garland.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $secondary_menu_heading: The title of the menu used by the secondary links.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['highlighted']: Items for the highlighted content region.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['header']: Items for the header region.
 * - $page['footer']: Items for the footer region.
 * - $page['bottom']: Items to appear at the bottom of the page below the footer.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see zen_preprocess_page()
 * @see template_process()
 */
?>

<div id="page-wrapper">
<div id="page">

    <table cellspacing="0" cellpadding="0" border="0" align="center" class="pmiMainWrapperTable">
      <tbody>
        <tr>
<!--          <td width="7" valign="top" class="bodyLeft"><img width="31" height="40" alt="none" src="themes/pmi/cms/images/headLeft.gif"></td>
-->          
			<td width="770" valign="top" align="center" class="mainTable"><div id="header">
              <div class="section clearfix">
                <?php if ($logo): ?>
                <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo"><img  class="pmiMainNavigation" src="<?php echo($base_path); ?>themes/pmi/cms/images/logo_.gif" alt="<?php print t('Home'); ?>" /></a>
                <div id="mainLinksBar">
                  <table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                      <tr>
                      	<td> <?php if (!$user->uid){ ?> <a href="<?php echo($base_path); ?>sitecontent"><img src="<?php echo($base_path); ?>themes/pmi/cms/images/memberLogin.gif" width="100" height="25" style="margin:36px 0 0 150px;"></a> <?php } else  { ?> <a href="<?php echo($base_path);?>/user/<?php echo($user->uid); ?>/edit" class="topNav welcome" style="margin:50px 0 0 150px;"> Welcome, <?php echo($user->name); ?> </a> <?php } ?> </td>
                      </tr>
                      <tr>
                        <td valign="bottom" height="25" align="right"><a class="topNav" href="<?php echo($base_path); ?>home">Home</a> <span class="txtTopNav"> | </span> <a class="topNav" href="<?php echo($base_path); ?>news">News</a> <span class="txtTopNav"> | </span> <a class="topNav" href="<?php echo($base_path); ?>newsletter">Newsletters</a> <span class="txtTopNav"> | </span> <a class="topNav" href="<?php echo($base_path); ?>events">Events</a> <span class="txtTopNav"> | </span> <a class="topNav" href="<?php echo($base_path); ?>contact_us">Contact Us</a> <span class="txtTopNav"> | </span> <a class="topNav" href="<?php echo($base_path); ?>sitemap">Sitemap</a> <span class="txtTopNav"> <?php if (!$user->uid){ ?> | </span> <a class="topNav" href="<?php echo($base_path); ?>user/register">Registration</a><?php } ?>  <?php if ($user->uid)  { ?> <span class="txtTopNav"> | </span> <a class="topNav" href="<?php echo($base_path); ?>user/logout">Logout</a> <?php } ?> </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <?php endif; ?>
                <div id="main_menu">
                  <table cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                      <tr>
                        <td><div id="ChapterDocuments" class="menu"><a href="<?php echo($base_path); ?>sitecontent"><img height="35" border="0" width="222" alt="My PMI KPC" src="<?php echo($base_path); ?>themes/pmi/cms/images/headMYPMIKPC.gif"></a></div></td>
                        <td><div id="PMIKnowledge" class="menu"><a href="<?php echo($base_path); ?>Knowledge"><img height="35" border="0" width="192" alt="PMI knowledge" src="<?php echo($base_path); ?>themes/pmi/cms/images/headPmiKnow.gif"></a><div class="sub_menu">
                                                  <ul style="display: none;" class="yellow">
                                                        <li><a href="<?php echo($base_path); ?>Knowledge"> What is PMI?</a></li>
                                                        <li><a href="<?php echo($base_path); ?>Membership"> PMI Membership</a></li>
                                                        <li><a href="<?php echo($base_path); ?>membership_benefits"> Membership Benefits</a></li>
                                                  </ul>
                                            </div>
                          </div></td>
                        <td><div id="Membership" class="menu"><a href="<?php echo($base_path); ?>Membership"><img height="35" border="0" width="144" alt="Programs" src="<?php echo($base_path); ?>themes/pmi/cms/images/headPROGRAMS.gif"></a><div class="sub_menu">
                                                      <ul style="display: none;" class="blue">
                                                            <li><a href="<?php echo($base_path); ?>PMP"> PMP</a></li>
                                                            <li><a href="<?php echo($base_path); ?>membership_benefits"> PMO</a></li>
                                                            <li><a href="<?php echo($base_path); ?>OPM3"> OPM3</a></li>
                                                            <li><a href="<?php echo($base_path); ?>mentoring"> Mentoring</a></li>
                                                            <li><a href="<?php echo($base_path); ?>Consupting"> Consupting</a></li>
                                                            <li><a href="<?php echo($base_path); ?>awards_2010"> Awards</a></li>
                                                            <li><a href="<?php echo($base_path); ?>Seminar"> Seminars</a></li>
                                                      </ul>
                                                </div>
                          </div></td>
                        <td><div id="BoardMembers" class="menu"><a href="<?php echo($base_path); ?>president_and_vice_president"><img height="35" border="0" width="192" alt="Board Members" src="<?php echo($base_path); ?>themes/pmi/cms/images/headBoardMem.gif"></a><div class="sub_menu">
                                                  <ul style="display: none;" class="purple">
                                                        <li><a href="volunteers"> Volunteers </a> </li>
                                                        <li><a href="president_and_vice_president"> President</a></li>
                                                        <li><a href="president_and_vice_president"> Vice President</a></li>
                                                        <li><a href="directors"> Directors</a></li>
                                                        <li><a href="previous_bod_members"> Previous Board Members</a> </li>
                                                  </ul>
                                            </div>
                          </div></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <?php print render($page['header']); ?> </div>
            </div>
            <!-- /.section, /#header -->
            <div id="main-wrapper">
              <div id="main" class="clearfix<?php if ($main_menu || $page['navigation']) { print ' with-navigation'; } ?>">
                <div class="pmi-main-content ">
                  <div class="section">
                    <?php //print render($page['highlighted']); ?>
                    <?php //print $breadcrumb; ?>
                    <!--<a id="main-content"></a>-->
                    <?php print render($title_prefix); ?>
                    <?php if ($title): ?>
                    <!--<h1 class="title" id="page-title"><?php //print $title; ?></h1>-->
                    <?php endif; ?>
                    <?php //print render($title_suffix); ?>
                    <?php //print $messages; ?>
                    <?php if ($tabs = render($tabs)): ?>
                    <!-- <div class="tabs"><?php //print $tabs; ?></div> -->
                    <?php endif; ?>
                    <?php //print render($page['help']); ?>
                    <?php if ($action_links): ?>
                    <ul class="action-links">
                      <?php print render($action_links); ?>
                    </ul>
                    <?php endif; ?>
                    <?php print render($page['sidebar_first']); ?> <?php print render($page['content']); ?>
                    <?php //print $feed_icons; ?>
                  </div>
                </div>
                <!-- /.section, /#content -->
                <?php if ($page['navigation'] || $main_menu): ?>
<!--                <div id="navigation">
                  <div class="section clearfix">
                    <?php /*print theme('links__system_main_menu', array(
          'links' => $main_menu,
          'attributes' => array(
            'id' => 'main-menu',
            'class' => array('links', 'clearfix'),
          ),
          'heading' => array(
            'text' => t('Main menu'),
            'level' => 'h2',
            'class' => array('element-invisible'),
          ),
        )); */?>
                    <?php //print render($page['navigation']); ?>
                  </div>
                </div>
-->                <!-- /.section, /#navigation -->
                <?php endif; ?>
                <?php //print render($page['sidebar_second']); ?>
              </div>
            </div>
            <!-- /#main, /#main-wrapper -->
            <?php //print render($page['content_footer']); ?>
            <?php //print render($page['footer']); ?>
      
      <!-- /#page, /#page-wrapper -->
      <table width="750" cellspacing="0" cellpadding="0" align="center">
        <tr>
          <td width="750" valign="top" height="75" bgcolor="#FFFFFF" align="left"><div id="footer_panel"><br>
              <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tbody>
                  <!--<tr>
                    <td>&nbsp;</td>
                  </tr>-->
                  <tr>
                    <td height="30" align="right" class="tableBlueBorder"><span class="txtFootNav"> <a class="footNav" href="<?php echo($base_path); ?>home"> Home </a> | <a class="footNav" href="<?php echo($base_path); ?>news"> News </a> | <a class="footNav" href="<?php echo($base_path); ?>Membership"> Programs </a> | <a class="footNav" href="<?php echo($base_path); ?>president_and_vice_president"> Board Members </a> | <a class="footNav" href="<?php echo($base_path); ?>Knowledge"> PMI Knowledge </a> | <a class="footNav" href="<?php echo($base_path); ?>Newsletter"> Newsletters </a> | <a class="footNav" href="<?php echo($base_path); ?>events"> Events </a> | <a class="footNav" href="<?php echo($base_path); ?>contact_us"> Contact Us </a> | <a class="footNav" href="<?php echo($base_path); ?>survey"> Survey </a> | <a class="footNav" href="<?php echo($base_path); ?>faqs"> FAQs </a> | <a class="footNav" href="<?php echo($base_path); ?>feedback"> Feedback | </a><a class="footNav" href="<?php echo($base_path); ?>sitemap"> Sitemap </a> </span></td>
                  </tr>
                  <tr>
                    <td height="25" align="left" class="txtGrayFoot"><table width="133" cellspacing="0" cellpadding="3" border="0" align="left">
                        <tbody>
                          <tr>
                            <td align="right"><a target="_blank" href="http://www.website-hit-counters.com/"> <img border="0" title="website-hit-counters.com" alt="website-hit-counters.com" src="http://www.website-hit-counters.com/cgi-bin/image.pl?URL=355115-5672"> </a></td>
                          </tr>
                        </tbody>
                      </table>
                      Copyright &copy; PMI Karachi Pakistan Chapter - All rights Reserved | <a class="topNav" href="<?php echo($base_path); ?>#"> Privacy Policy </a></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
                </tbody>
              </table>
            </div></td>
        </tr>
        </td>
        
      </table>
      </td>
      
<!--      <td width="7" valign="top" class="bodyRight"><img width="31" height="40" alt="none" src="themes/pmi/cms/images/headRight.gif"></td>
-->      </tr>
      
    </table>
    </td>
  </tr>
  <tr>
    <td valign="top" height="42"><table width="1000" cellspacing="0" cellpadding="0" border="0">
        <tbody>
          <tr>
<!--            <td align="right"><img height="42" width="116" alt="none" src="themes/pmi/cms/images/footerleft.gif"></td>
            <td class="footerCenter">&nbsp;</td>
            <td align="left"><img height="42" width="116" alt="none" src="themes/pmi/cms/images/footerRight.gif"></td>
-->          </tr>
        </tbody>
      </table>
      
</div>
</div>
<?php //print render($page['bottom']); ?>
