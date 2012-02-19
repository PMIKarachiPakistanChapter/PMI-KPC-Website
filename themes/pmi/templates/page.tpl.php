<?php
//Find if the current page is an overlay
$overlay = (overlay_get_mode() == 'child');
?>

<div id="page-wrapper">
    <div id="page">

        <table cellspacing="0" cellpadding="0" border="0" align="center" class="pmiMainWrapperTable">
            <tbody>
                <tr>
        <!--          <td width="7" valign="top" class="bodyLeft"><img width="31" height="40" alt="none" src="themes/pmi/cms/images/headLeft.gif"></td>
                    -->          
                    <td <?php if (!$overlay) print 'width="770"' ?> valign="top" align="center" class="mainTable">
                        <?php if (!$overlay): ?>
                        <div id="header">
                            <div class="section clearfix">
                                <?php if ($logo): ?>
                                    <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo"><img  class="pmiMainNavigation" src="/<?php echo($directory); ?>/cms/images/logo_.gif" alt="<?php print t('Home'); ?>" /></a>
                                    <div style="float:right; margin-bottom:-30px; position:relative;">
  <div style="border:1px gray solid; width:222px; height:36px; border-radius: 10px;">
    <div style="margin-top:11px; width: 80px; float:left; font-size: 13px; color:#797979; ">Follow Us:</div>
    <div style="float:right; width:60px; margin: 6px 5px 0 0;">
      <div style="margin-right:2px;"><a href="https://www.facebook.com/pages/PMI-Karachi-Pakistan-Chapter/101469309927901"> <img src="/<?php echo($directory); ?>/cms/images/fb.png" style="float:right;"></a></div>
      <div><a href="http://www.linkedin.com/company/258944?trk=tyah"> <img src="/<?php echo($directory); ?>/cms/images/ln.png" style="margin-right:2px;"></a></div>
    </div>
  </div>
</div>
                                    <div id="mainLinksBar">
                                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                            <tbody>
                                                <tr>
                                                    <td> <?php if (!$user->uid) { ?> <a href="<?php echo($base_path); ?>sitecontent"><img src="/<?php echo($directory); ?>/cms/images/memberLogin.gif" width="100" height="25" style="margin:36px 0 0 150px;"></a> <?php } else { ?> <a href="<?php echo($base_path); ?>user/<?php echo($user->uid); ?>/edit" class="topNav welcome" style="margin:50px 0 0 150px;"> Welcome, <?php echo($user->name); ?> </a> <?php } ?> </td>
                                                </tr>
                                                <tr>
                                                    <td valign="bottom" height="25" align="right"><a class="topNav" href="<?php echo($base_path); ?>home"> Home </a> <span class="txtTopNav"> | </span> <a class="topNav" href="<?php echo($base_path); ?>news"> News </a> <span class="txtTopNav"> | </span> <a class="topNav" href="<?php echo($base_path); ?>newsletter"> Newsletters </a> <span class="txtTopNav"> | </span> <a class="topNav" href="<?php echo($base_path); ?>events"> Events </a> <span class="txtTopNav"> | </span> <a class="topNav" href="<?php echo($base_path); ?>contact_us"> Contact Us </a> <span class="txtTopNav"> | </span> <a class="topNav" href="<?php echo($base_path); ?>sitemap"> Sitemap </a> <?php if ($user->uid) { ?> <span class="txtTopNav"> | </span> <a class="topNav" href="<?php echo($base_path); ?>user/logout"> Logout </a> <?php } ?> </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                                <div id="main_menu">        
                                    <table width="750" border="1">
                                        <tr>
                                            <td>
                                                <div>
                                                    <img height="35" border="0" width="52" src="/<?php echo($directory); ?>/cms/images/headMYPMIKPCLeft.gif">
                                                </div>
                                            </td>
                                            <td>
                                                <div id="ChapterDocuments" class="menu">                        
                                                    <a href="<?php echo($base_path); ?>sitecontent">
                                                        <img height="35" border="0" width="170" alt="My PMI KPC" src="/<?php echo($directory); ?>/cms/images/headMYPMIKPC.gif">
                                                    </a>
                                                    <div class="sub_menu">
                                                        <ul style="display: none;" class="red">
                                                            <li><a href="<?php echo($base_path); ?>contact_us"> Email</a></li>
                                                            <li><a href="<?php echo($base_path); ?>pmi_jobs"> PMI Jobs</a></li>
                                                            <li><a href="<?php echo($base_path); ?>elections2011"> Elections</a></li>
                                                            <li><a href="<?php echo($base_path); ?>feedback"> Feedback</a></li>
                                                        </ul>
                                                    </div>
                                                </div></td>
                                            <td><div id="PMIKnowledge" class="menu"><a href="<?php echo($base_path); ?>Knowledge"><img height="35" border="0" width="192" alt="PMI knowledge" src="/<?php echo($directory); ?>/cms/images/headPmiKnow.gif"></a><div class="sub_menu">
                                                        <ul style="display: none;" class="yellow">
                                                            <li><a href="<?php echo($base_path); ?>Knowledge"> What is PMI?</a></li>
                                                            <li><a href="<?php echo($base_path); ?>Membership"> PMI Membership</a></li>
                                                            <li><a href="<?php echo($base_path); ?>membership_benefits"> Membership Benefits</a></li>
                                                            <li><a href="<?php echo($base_path); ?>previous_events"> Previous Events</a></li>
                                                            <li><a href="<?php echo($base_path); ?>photo_gallery"> Photo Gallery</a></li>
                                                            <li><a href="<?php echo($base_path); ?>limc_grad"> PMI LIMC Grads</a></li>
                                                        </ul>
                                                    </div>
                                                </div></td>
                                            <td><div id="Membership" class="menu"><a href="<?php echo($base_path); ?>programs"><img height="35" border="0" width="144" alt="Programs" src="/<?php echo($directory); ?>/cms/images/headPROGRAMS.gif"></a><div class="sub_menu">
                                                        <ul style="display: none;" class="blue">
                                                            <li><a href="<?php echo($base_path); ?>PMP"> PMP</a></li>
                                                            <li><a href="<?php echo($base_path); ?>PMO"> PMO</a></li>
                                                            <li><a href="<?php echo($base_path); ?>OPM3"> OPM3</a></li>
                                                            <li><a href="<?php echo($base_path); ?>mentoring"> Mentoring</a></li>
                                                            <li><a href="<?php echo($base_path); ?>Consulting"> Consulting</a></li>
                                                            <li><a href="<?php echo($base_path); ?>award-of-year"> Awards</a></li>
                                                            <li><a href="<?php echo($base_path); ?>Seminar"> Seminars</a></li>
                                                        </ul>
                                                    </div>
                                                </div></td>
                                            <td><div id="BoardMembers" class="menu"><a href="<?php echo($base_path); ?>president_and_vice_president"><img height="35" border="0" width="192" alt="Board Members" src="/<?php echo($directory); ?>/cms/images/headBoardMem.gif"></a><div class="sub_menu">
                                                        <ul style="display: none;" class="purple">
                                                            <li><a href="volunteers"> Volunteers </a> </li>
                                                            <li><a href="president_and_vice_president"> President & VPs</a></li>
                                                            <li><a href="directors"> Directors</a></li>
                                                            <li><a href="previous_bod_members"> Previous Board Members</a> </li>
                                                        </ul>
                                                    </div>
                                                </div></td>
                                        </tr>
                                    </table>




                                    <table cellspacing="0" cellpadding="0" border="0">
                                        <tbody>
                                            <tr>




                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                                <?php print render($page['header']); ?> </div>
                        </div>
                        <?php endif; ?>
                        <!-- /.section, /#header -->
                        <div id="main-wrapper">
                            <div id="main" class="clearfix<?php if ($main_menu || $page['navigation']) {
                                    print ' with-navigation';
                                } ?>">
                                <div class="pmi-main-content ">
                                    <div class="section">
                                        <?php //print render($page['highlighted']);  ?>
                                        <?php //print $breadcrumb; ?>
                                        <!--<a id="main-content"></a>-->
                                        <?php print render($title_prefix); ?>
                                        <?php if ($title): ?>
                                            <!--<h1 class="title" id="page-title"><?php //print $title; ?></h1>-->
                                        <?php endif; ?>
                                        <?php //print render($title_suffix); ?>
                                        <?php //print $messages;  ?>
                                        <?php if ($tabs = render($tabs)): ?>
                                            <!-- <div class="tabs"><?php //print $tabs; ?></div> -->
                                        <?php endif; ?>
                                            <?php //print render($page['help']);  ?>
                                            <?php if ($action_links): ?>
                                            <ul class="action-links">
                                            <?php print render($action_links); ?>
                                            </ul>
                                        <?php endif; ?>
<?php print render($page['sidebar_first']); ?> <?php print render($page['content']); ?>
<?php //print $feed_icons;  ?>
                                    </div>
                                </div>
                                <!-- /.section, /#content -->
                                <?php if ($page['navigation'] || $main_menu): ?>
                                    <!--                <div id="navigation">
                                                      <div class="section clearfix">
                                    <?php /* print theme('links__system_main_menu', array(
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
                                      )); */ ?>
    <?php //print render($page['navigation']);  ?>
                                                      </div>
                                                    </div>
                                    -->                <!-- /.section, /#navigation -->
<?php endif; ?>
<?php //print render($page['sidebar_second']);  ?>
                            </div>
                        </div>
                        <!-- /#main, /#main-wrapper -->
<?php //print render($page['content_footer']);  ?>
<?php //print render($page['footer']);  ?>

                        <!-- /#page, /#page-wrapper -->
                        <?php if (!$overlay): ?>
                        <table width="750" cellspacing="0" cellpadding="0" align="center">
                            <tr>
                                <td width="750" valign="top" height="75" bgcolor="#FFFFFF" align="left">
                                <div id="footer_panel">
              	
                  <table width="100%" cellspacing="0" cellpadding="0" border="0">
                      <tr>
                        <td height="30" align="right" class="tableBlueBorder"><span class="txtFootNav"> <a class="footNav" href="<?php echo($base_path); ?>home"> Home</a> | <a class="footNav" href="<?php echo($base_path); ?>news"> News</a> | <a class="footNav" href="<?php echo($base_path); ?>programs"> Programs</a> | <a class="footNav" href="<?php echo($base_path); ?>president_and_vice_president"> Board Members</a> | <a class="footNav" href="<?php echo($base_path); ?>Knowledge"> PMI Knowledge</a> | <a class="footNav" href="<?php echo($base_path); ?>Newsletter"> Newsletters</a> | <a class="footNav" href="<?php echo($base_path); ?>events"> Events</a> | <a class="footNav" href="<?php echo($base_path); ?>bodarea"> BOD Area</a> | <a class="footNav" href="<?php echo($base_path); ?>contact_us"> Contact Us</a> | <a class="footNav" href="<?php echo($base_path); ?>survey"> Survey</a> | <a class="footNav" href="<?php echo($base_path); ?>faqs"> FAQs</a> | <a class="footNav" href="<?php echo($base_path); ?>feedback"> Feedback</a> | <a class="footNav" href="<?php echo($base_path); ?>sitemap"> Sitemap</a> </span></td>
                      </tr>
                      <tr>
                        <td height="25" align="left" class="txtGrayFoot">
                        <div>
                            <table width="133" cellspacing="0" cellpadding="3" border="0" align="left">
                              <tbody>
                                <tr>
                                  <td><div id="glutton_counter_14447"></div>
                                    <p id="glutton_backlink_14447"><span></span> <a target="_blank" href="http://www.free-website-hit-counters.com/" style="margin-right: -22px;  font-size: 0px;">w</a> <span></span></p>
                                    <script type="text/javascript" src="http://counter.website-hit-counters.com/white-on-black/14447"></script></td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                       </td>
                      </tr>
                      <tr>
                        <td align="left" class="txtGrayFoot">
                        	<div> Copyright &copy; PMI Karachi Pakistan Chapter - All rights Reserved | <a class="topNav" href="<?php echo($base_path); ?>#"> Privacy Policy </a></div>
                        </td>
                      </tr>
                  </table>
                </div>
                				</td>
                                </tr>
                            </td>
                        </table>
                        <?php endif; ?>
                    </td>

<!--      <td width="7" valign="top" class="bodyRight"><img width="31" height="40" alt="none" src="themes/pmi/cms/images/headRight.gif"></td>
        -->      </tr>

        </table>
        </td>
        </tr>
      <!--  <tr>
          <td valign="top" height="42">
              <table width="1000" cellspacing="0" cellpadding="0" border="0">
                  <tbody>
                     <tr>
                     <td align="right"><img height="42" width="116" alt="none" src="themes/pmi/cms/images/footerleft.gif"></td>
                      <td class="footerCenter">&nbsp;</td>
                      <td align="left"><img height="42" width="116" alt="none" src="themes/pmi/cms/images/footerRight.gif"></td>
                   </tr> 
                  </tbody>
                </table>
           </td>
        </tr>
        -->      
    </div>
</div>
<?php //print render($page['bottom']);  ?>

<?php if ($overlay): ?>
<style type="text/css">
#overlay {
  min-width: 0px;
  width: auto;
}
#page-wrapper,
.region-bottom {
  width: auto;
}
.pmiMainWrapperTable {
  width: auto;
}
.pmi-main-content {
  width: auto;
}
</style>
<?php endif; ?>
