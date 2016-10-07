<!DOCTYPE html>
<!--if IE 8html.ie8.no-js(lang='en', data-ng-app='bungomedia')  
-->
<!--if IE 9html.ie9.no-js(lang='en', data-ng-app='bungomedia')  
-->
<!-- [if !IE] <!-->
<html lang="en" data-ng-app="bungomedia">
  <!-- <![endif]-->
  <!-- BEGIN HEAD-->
  <head>
    <title data-ng-bind="'Bungomedia'"></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="" name="description">
    <meta content="" name="author">
    <!-- BEGIN GLOBAL MANDATORY STYLES-->
    <!-- END THEME STYLES-->
    <script>
      var FACEBOOK_API_KEY = '{{ config("facebook.app_id") }}';
      
    </script>

    <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.css" />
    <link rel="stylesheet" href="/bower_components/font-awesome/css/font-awesome.css" />
    <link rel="stylesheet" href="/bower_components/weather-icons/css/weather-icons.css" />
    <link rel="stylesheet" href="/bower_components/weather-icons/css/weather-icons.min.css" />
    <link rel="stylesheet" href="/bower_components/animate.css/animate.css" />
    <link rel="stylesheet" href="/bower_components/angular-loading-bar/build/loading-bar.css" />
    <link rel="stylesheet" href="/bower_components/bootstrap-daterangepicker/daterangepicker-bs3.css" />
    <link rel="stylesheet" href="/bower_components/angular-ui-tree/dist/angular-ui-tree.min.css" />
    <link rel="stylesheet" href="/bower_components/simple-line-icons/css/simple-line-icons.css" />
    <link rel="stylesheet" href="/bower_components/angular-toastr/dist/angular-toastr.css" />
    <link rel="stylesheet" href="/bower_components/angular-bootstrap-nav-tree/dist/abn_tree.css" />
    <link rel="stylesheet" href="/bower_components/chosen/chosen.min.css" />
    <link rel="stylesheet" href="/bower_components/angular-ui-select/dist/select.css" />
    <link rel="stylesheet" href="/bower_components/angular-bootstrap-colorpicker/css/colorpicker.css" />
    <link rel="stylesheet" href="/bower_components/ngImgCrop/compile/minified/ng-img-crop.css" />
    <link rel="stylesheet" href="/bower_components/datatables/media/css/jquery.dataTables.css" />
    <link rel="stylesheet" href="/bower_components/angular-ui-grid/ui-grid.css" />
    <link rel="stylesheet" href="/bower_components/ng-table/ng-table.css" />
    <link rel="stylesheet" href="/bower_components/morrisjs/morris.css" />
    <link rel="stylesheet" href="/bower_components/rickshaw/rickshaw.min.css" />
    <link rel="stylesheet" href="/bower_components/owl-carousel/owl-carousel/owl.carousel.css" />
    <link rel="stylesheet" href="/bower_components/owl-carousel/owl-carousel/owl.theme.css" />
    <link rel="stylesheet" href="/bower_components/fullcalendar/fullcalendar.css" />
    <link rel="stylesheet" href="/bower_components/ng-tags-input/ng-tags-input.min.css" />
    <link rel="stylesheet" href="/bower_components/switchery/dist/switchery.min.css" />
    <link rel="stylesheet" href="/bower_components/angular-xeditable/dist/css/xeditable.css" />
    <link rel="stylesheet" href="/bower_components/ng-tags-input/ng-tags-input.bootstrap.min.css" />
    <link rel="stylesheet" href="/bower_components/angular-ui-select/dist/select.min.css" />
    <link rel="stylesheet" href="/bower_components/flexslider/flexslider.css" />
    <!-- endbower -->
    <!-- endbuild -->
    <!-- build:css(.tmp) styles/main.css -->
    <link rel="stylesheet" href="css/main.css">
    <!-- endbuild -->
  </head>
  <!-- END HEAD-->
  <!-- BEGIN BODY-->
  <body ng-controller="AppController" id="minovate" class="hz-menu scheme-default default-scheme-color header-fixed aside-fixed">

    @yield('content')

    <!-- Page Loader -->
    <div id="pageloader" page-loader></div>

    <!-- build:js(.) scripts/oldieshim.js -->
    <!--[if lt IE 9]>
    <script src="/bower_components/es5-shim/es5-shim.js"></script>
    <script src="/bower_components/json3/lib/json3.min.js"></script>
    <![endif]-->
    <!-- endbuild -->

    <!-- BEGIN CORE PLUGINS-->
    <script src="/bower_components/jquery/jquery.min.js"></script>
    <script src="/bower_components/angular/angular.js"></script>
    <script src="/bower_components/json3/lib/json3.js"></script>
    <script src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="/bower_components/angular-resource/angular-resource.js"></script>
    <script src="/bower_components/angular-cookies/angular-cookies.js"></script>
    <script src="/bower_components/angular-sanitize/angular-sanitize.js"></script>
    <script src="/bower_components/angular-animate/angular-animate.js"></script>
    <script src="/bower_components/angular-touch/angular-touch.js"></script>
    <script src="/bower_components/angular-fontawesome/dist/angular-fontawesome.js"></script>
    <script src="/bower_components/angular-bootstrap/ui-bootstrap-tpls.js"></script>
    <script src="/bower_components/jquery.slimscroll/jquery.slimscroll.min.js"></script>
    <script src="/bower_components/jquery.sparkline/index.js"></script>
    <script src="/bower_components/angular-loading-bar/build/loading-bar.js"></script>
    <script src="/bower_components/angular-ui-router/release/angular-ui-router.js"></script>
    <script src="/bower_components/angular-ui-utils/ui-utils.js"></script>
    <script src="/bower_components/moment/moment.js"></script>
    <script src="/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="/bower_components/ng-bs-daterangepicker/src/ng-bs-daterangepicker.js"></script>
    <script src="/bower_components/angular-momentjs/angular-momentjs.js"></script>
    <script src="/bower_components/angular-fullscreen/src/angular-fullscreen.js"></script>
    <script src="/bower_components/angular-ui-tree/dist/angular-ui-tree.js"></script>
    <script src="/bower_components/html.sortable/dist/html.sortable.js"></script>
    <script src="/bower_components/angular-toastr/dist/angular-toastr.tpls.js"></script>
    <script src="/bower_components/angular-bootstrap-nav-tree/dist/abn_tree_directive.js"></script>
    <script src="/bower_components/oclazyload/dist/ocLazyLoad.min.js"></script>
    <script src="/bower_components/chosen/chosen.jquery.min.js"></script>
    <script src="/bower_components/angular-ui-select/dist/select.js"></script>
    <script src="/bower_components/textAngular/dist/textAngular.min.js"></script>
    <script src="/bower_components/angular-bootstrap-colorpicker/js/bootstrap-colorpicker-module.js"></script>
    <script src="/bower_components/angular-file-upload/angular-file-upload.min.js"></script>
    <script src="/bower_components/ngImgCrop/compile/minified/ng-img-crop.js"></script>
    <script src="/bower_components/datatables/media/js/jquery.dataTables.js"></script>
    <script src="/bower_components/angular-datatables/dist/angular-datatables.min.js"></script>
    <script src="/bower_components/angular-datatables/dist/plugins/bootstrap/angular-datatables.bootstrap.min.js"></script>
    <script src="/bower_components/angular-datatables/dist/plugins/colreorder/angular-datatables.colreorder.min.js"></script>
    <script src="/bower_components/angular-datatables/dist/plugins/columnfilter/angular-datatables.columnfilter.min.js"></script>
    <script src="/bower_components/angular-datatables/dist/plugins/colvis/angular-datatables.colvis.min.js"></script>
    <script src="/bower_components/angular-datatables/dist/plugins/fixedcolumns/angular-datatables.fixedcolumns.min.js"></script>
    <script src="/bower_components/angular-datatables/dist/plugins/fixedheader/angular-datatables.fixedheader.min.js"></script>
    <script src="/bower_components/angular-datatables/dist/plugins/scroller/angular-datatables.scroller.min.js"></script>
    <script src="/bower_components/angular-datatables/dist/plugins/tabletools/angular-datatables.tabletools.min.js"></script>
    <script src="/bower_components/angular-xeditable/dist/js/xeditable.min.js"></script>
    <script src="/bower_components/angular-ui-grid/ui-grid.js"></script>
    <script src="/bower_components/ng-table/ng-table.js"></script>
    <script src="/bower_components/angular-smart-table/dist/smart-table.js"></script>
    <script src="/bower_components/raphael/raphael.js"></script>
    <script src="/bower_components/mocha/mocha.js"></script>
    <script src="/bower_components/morrisjs/morris.js"></script>
    <script src="/bower_components/flot/jquery.flot.js"></script>
    <script src="/bower_components/flot/jquery.flot.resize.js"></script>
    <script src="/bower_components/flot/jquery.flot.time.js"></script>
    <script src="/bower_components/flot.tooltip/js/jquery.flot.tooltip.js"></script>
    <script src="/bower_components/angular-flot/angular-flot.js"></script>
    <script src="/bower_components/flot-spline/js/jquery.flot.spline.min.js"></script>
    <script src="/bower_components/d3/d3.js"></script>
    <script src="/bower_components/rickshaw/rickshaw.js"></script>
    <script src="/bower_components/angular-rickshaw/rickshaw.js"></script>
    <script src="/bower_components/owl-carousel/owl-carousel/owl.carousel.min.js"></script>
    <script src="/bower_components/lodash/lodash.js"></script>
    <script src="/bower_components/angular-google-maps/dist/angular-google-maps.js"></script>
    <script src="/bower_components/jquery-ui/ui/jquery-ui.js"></script>
    <script src="/bower_components/fullcalendar/fullcalendar.js"></script>
    <script src="/bower_components/angular-ui-calendar/src/calendar.js"></script>
    <script src="/bower_components/jquery.easy-pie-chart/dist/angular.easypiechart.min.js"></script>
    <script src="/bower_components/ng-tags-input/ng-tags-input.min.js"></script>
    <script src="/bower_components/angular-route/angular-route.min.js"></script>
    <script src="/bower_components/angular-facebook/lib/angular-facebook.js"></script>
    <script src="/bower_components/switchery/dist/switchery.min.js"></script>
    <script src="/bower_components/ng-flow/dist/ng-flow-standalone.js"></script>
    <script src="/bower_components/ng-switchery/src/ng-switchery.js"></script>
    <script src="/bower_components/checklist-model/checklist-model.js"></script>
    <script src="/bower_components/angular-ui-select/dist/select.js"></script>
    <script src="/bower_components/flexslider/jquery.flexslider.js"></script>
    <script src="/bower_components/angular-flexslider/angular-flexslider.js"></script>
    <!-- END CORE ANGULARJS PLUGINS-->

    <!-- BEGIN APP LEVEL ANGULARJS SCRIPTS-->
    <script type="text/javascript" src="/js/app.js"></script>
    <script type="text/javascript" src="/js/config.js"></script>
    <script type="text/javascript" src="/js/filters.js"></script>
    <script type="text/javascript" src="/js/constants.js"></script>
    <script type="text/javascript" src="/js/init.js"></script>

    <!-- Directives-->
    <script src="js/directives/core/navcollapse.js"></script>
    <script src="js/directives/core/slimscroll.js"></script>
    <script src="js/directives/core/sparkline.js"></script>
    <script src="js/directives/core/collapsesidebar.js"></script>
    <script src="js/directives/core/ripple.js"></script>
    <script src="js/directives/core/pageloader.js"></script>
    <script src="js/directives/core/daterangepicker.js"></script>
    <script src="js/directives/core/tilecontrolclose.js"></script>
    <script src="js/directives/core/tilecontroltoggle.js"></script>
    <script src="js/directives/core/tilecontrolrefresh.js"></script>
    <script src="js/directives/core/tilecontrolfullscreen.js"></script>
    <script src="js/directives/core/prettyprint.js"></script>
    <script src="js/directives/core/lazymodel.js"></script>
    <script src="js/directives/core/activatebutton.js"></script>
    <script src="js/directives/core/toastrinject.js"></script>
    <script src="js/directives/core/setnganimate.js"></script>
    <script src="js/directives/core/onblurvalidation.js"></script>
    <script src="js/directives/core/formsubmit.js"></script>
    <script src="js/directives/core/check-toggler.js"></script>
    <script src="js/directives/core/chart-morris.js"></script>
    <script src="js/directives/core/gaugechart.js"></script>
    <script src="js/directives/core/wrap-owlcarousel.js"></script>
    <script src="js/directives/core/todofocus.js"></script>
    <script src="js/directives/core/todoescape.js"></script>
    <script src="js/directives/core/clock.js"></script>
    <script src="js/directives/core/active-toggle.js"></script>
    <script src="js/directives/core/vector-map.js"></script>
    <script src="js/directives/core/anchor-scroll.js"></script>
    <script src="js/directives/core/offcanvas-sidebar.js"></script>
    <script src="js/directives/core/submitvalidate.js"></script>
    <script src="js/directives/core/native-tab.js"></script>
    <script src="js/directives/core/addcondition-directive.js"></script>

    <script src="js/directives/metrics-data-table/metrics-data-table.js"></script>
    <script src="js/directives/datepicker/datepicker.js"></script>
    <script src="js/directives/green-checkbox/green-checkbox.js"></script>
    <script src="js/directives/directives.js"></script>
    <!-- END directives-->

    <!--Application Services-->
    <script src="/js/services/utils.js"></script>
    <script src="/js/services/metricsDataService.js"></script>
    <script src="/js/services/graphDataService.js"></script>
    <script src="/js/services/userPreference.js"></script>
    <script src="/js/services/dialogService.js"></script>
    <!--Application API Factories-->
    <script type="text/javascript" src="/js/apis/auth.js"></script>
    <script type="text/javascript" src="/js/apis/preference.js"></script>
    <!-- User API -->
    <script type="text/javascript" src="/js/apis/user/account.js"></script>
    <script type="text/javascript" src="/js/apis/user/ad.js"></script>
    <script type="text/javascript" src="/js/apis/user/adwizard.js"></script>
    <script type="text/javascript" src="/js/apis/user/adset.js"></script>
    <script type="text/javascript" src="/js/apis/user/campaign.js"></script>
    <script type="text/javascript" src="/js/apis/user/cart.js"></script>
    <script type="text/javascript" src="/js/apis/user/category.js"></script>
    <script type="text/javascript" src="/js/apis/user/product.js"></script>
    <script type="text/javascript" src="/js/apis/user/rule.js"></script>
    <!-- Admin API -->
    <script type="text/javascript" src="/js/apis/admin/user.js"></script>
    <!--Application Controllers-->
    <!-- Layout-->
    <script type="text/javascript" src="/pages/layout/layout.js"></script>
    <script type="text/javascript" src="/pages/layout/footer.js"></script>
    <script type="text/javascript" src="/pages/layout/header.js"></script>
    <script type="text/javascript" src="/pages/layout/nav.js"></script>
    <script type="text/javascript" src="/pages/layout/rightbar.js"></script>
    <!-- Account -->
    <script type="text/javascript" src="/pages/account/settings.js"></script>
    <script type="text/javascript" src="/pages/account/accounts.js"></script>
    <script type="text/javascript" src="/pages/account/add-account.js"></script>
    <!-- Statistics -->
    <script src="/pages/statistics/statistics.js"></script>
    <script src="/pages/statistics/campaigns.js"></script>
    <script src="/pages/statistics/adsets.js"></script>
    <script src="/pages/statistics/ads.js"></script>
    <!-- Ads Wizard -->
    <script src="/pages/adwizard/adwizard.js"></script>
    <script src="/pages/adwizard/steps/goal.js"></script>
    <script src="/pages/adwizard/steps/create.js"></script>
    <script src="/pages/adwizard/steps/create/creative.js"></script>
    <script src="/pages/adwizard/steps/create/targeting.js"></script>
    <script src="/pages/adwizard/steps/create/campaign_settings.js"></script>
    <script src="/pages/adwizard/steps/finish.js"></script>
    <!-- Automatic Rules -->
    <script src="/pages/rules/list.js"></script>
    <!-- Landing -->
    <script src="/pages/landing/login.js"></script>

    <!-- END APP LEVEL ANGULARJS SCRIPTS-->
  </body>
  <!-- END BODY-->
</html>