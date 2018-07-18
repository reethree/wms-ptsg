<header class="main-header">

    <!-- Logo -->
    <a href="{{route('index')}}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>L</b>TT</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Lautan</b>Tirta</span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
<!--      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>-->
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="hidden-xs">{{ \Auth::getUser()->name }}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- Menu Footer-->
              <li class="user-footer">
<!--                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>-->
                <div class="pull-right">
                  <a href="{{ route('logout') }}" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>

    </nav>
  </header>
<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar" style="height: auto;">
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu tree" data-widget="tree">
        <li>
          <a href="{{route('index')}}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Master Data</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('consolidator-index') }}">Consolidator</a></li>
            <li><a href="{{ route('depomty-index') }}">Depo MTY</a></li>
            <li><a href="{{ route('lokasisandar-index') }}">Lokasi Sandar</a></li>
            <li><a href="{{ route('negara-index') }}">Negara</a></li>
            <li><a href="{{ route('packing-index') }}">Packing</a></li>
            <li><a href="{{ route('pelabuhan-index') }}">Pelabuhan</a></li>
            <li><a href="{{ route('perusahaan-index') }}">Perusahaan</a></li>
            <li><a href="{{ route('tpp-index') }}">TPP</a></li>
            <li><a href="{{ route('shippingline-index') }}">Shipping Line</a></li>
            <li><a href="{{ route('eseal-index') }}">E-Seal</a></li>
            <li><a href="{{ route('vessel-index') }}">Vessel</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-th"></i> <span>Import LCL</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a tabindex="-1" href="{{ route('lcl-register-index') }}">Register</a></li>
            <li><a tabindex="-1" href="{{ route('lcl-manifest-index') }}">Manifest</a></li>
            <li><a tabindex="-1" href="{{ route('lcl-dispatche-index') }}">Dispatche E-Seal</a></li>
            
            <li class="treeview">
              <a href="#"><i class="fa fa-circle-o"></i> Realisasi
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ route('lcl-realisasi-gatein-index') }}">Masuk / Gate In</a></li>
                <li><a href="{{ route('lcl-realisasi-stripping-index') }}">Stripping</a></li>
                <li><a href="{{ route('lcl-realisasi-buangmty-index') }}">Buang MTY</a></li>
              </ul>
            </li>
            
            <li class="treeview">
              <a href="#"><i class="fa fa-circle-o"></i> Delivery
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ route('lcl-delivery-behandle-index') }}">Behandle</a></li>
                <li><a href="{{ route('lcl-delivery-release-index') }}">Release / Gate Out</a></li>
              </ul>
            </li>
            
            <li class="treeview">
              <a href="#"><i class="fa fa-circle-o"></i> Report
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ route('lcl-report-container') }}">Report Container</a></li>
                <li><a href="{{ route('lcl-report-inout') }}">Report Stock</a></li>    
                <li><a href="{{ route('lcl-report-longstay') }}">Inventory</a></li>
              </ul>
            </li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-th"></i> <span>Import FCL</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a tabindex="-1" href="{{ route('fcl-register-index') }}">Register</a></li>
            <li><a tabindex="-1" href="{{ route('fcl-dispatche-index') }}">Dispatche E-Seal</a></li>
            
            <li class="treeview">
              <a href="#"><i class="fa fa-circle-o"></i> Realisasi
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ route('fcl-realisasi-gatein-index') }}">Masuk / Gate In</a></li>
              </ul>
            </li>
            
            <li class="treeview">
              <a href="#"><i class="fa fa-circle-o"></i> Delivery
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ route('fcl-delivery-behandle-index') }}">Behandle</a></li>
                <li><a href="{{ route('fcl-delivery-release-index') }}">Release / Gate Out</a></li>
              </ul>
            </li>
            
            <li class="treeview">
              <a href="#"><i class="fa fa-circle-o"></i> Report
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ route('fcl-report-rekap') }}">Report Container</a></li>
                <li><a href="{{ route('fcl-report-longstay') }}">Inventory</a></li>
              </ul>
            </li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-pie-chart"></i>
            <span>TPS Online</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="treeview">
              <a href="#"><i class="fa fa-circle-o"></i> Data
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ route('gudang-index') }}">Gudang</a></li>
                <li><a href="{{ route('pelabuhandn-index') }}">Pelabuhan DN</a></li>
                <li><a href="{{ route('pelabuhanln-index') }}">Pelabuhan LN</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#"><i class="fa fa-circle-o"></i> Penerimaan Data
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ route('tps-responPlp-index') }}">Data Respon PLP</a></li>
                <li><a href="{{ route('tps-responBatalPlp-index') }}">Data Respon Batal PLP</a></li>
                <li><a href="{{ route('tps-obLcl-index') }}">Data OB LCL</a></li>
                <li><a href="{{ route('tps-obFcl-index') }}">Data OB FCL</a></li>
                <li><a href="{{ route('tps-spjm-index') }}">Data SPJM</a></li>
                <li><a href="{{ route('tps-dokManual-index') }}">Data Dok Manual</a></li>
                <li><a href="{{ route('tps-sppbPib-index') }}">Data SPPB</a></li>
                <li><a href="{{ route('tps-sppbBc-index') }}">Data SPPB BC23</a></li>
                <li><a href="{{ route('tps-infoNomorBc-index') }}">Info Nomor BC11</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#"><i class="fa fa-circle-o"></i> Pengiriman Data
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="treeview">
                    <a href="#"><i class="fa fa-circle-o"></i> COARI (Cargo Masuk)
                      <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                      </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('tps-coariCont-index') }}">Coari Cont</a></li>
                        <li><a href="{{ route('tps-coariKms-index') }}">Coari KMS</a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#"><i class="fa fa-circle-o"></i> CODECO (Cargo Keluar)
                      <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                      </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('tps-codecoContFcl-index') }}">Codeco Cont FCL</a></li>
                        <li><a href="{{ route('tps-codecoContBuangMty-index') }}">Codeco Cont Buang MTY</a></li>
                        <li><a href="{{ route('tps-codecoKms-index') }}">Codeco KMS</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('tps-realisasiBongkarMuat-index') }}">Realisasi Bongkar Muat</a></li>
                <li><a href="{{ route('tps-laporanYor-index') }}">Laporan YOR</a></li>
              </ul>
            </li>
            
          </ul>
        </li>
        <li class="treeview">
            <a href="#">
              <i class="fa fa-money"></i>
              <span>Invoice</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
                <li class="treeview">
                    <a href="#"><i class="fa fa-circle-o"></i> LCL
                      <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                      </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('billing-template')}}">Billing Template</a></li>
<!--                        <li><a href="{{route('invoice-tarif-index')}}">Data Tarif</a></li>--> 
                        <li><a href="{{route('invoice-release-index')}}">Data Release/Gate Out</a></li>
                        <li><a href="{{route('invoice-index')}}">Data Invoice</a></li>
                        <li class="treeview">
                            <a href="#">
                              <i class="fa fa-archive"></i>
                              <span>Mechanic</span>
                              <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                              </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{route('mechanic-tarif')}}">Tarif Mechanic</a></li>
                                <li><a href="{{route('mechanic-container')}}">Data Container</a></li>
                                <li><a href="{{route('mechanic-rekap')}}">Rekap Mechanic</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
<!--                <li class="treeview">
                    <a href="#"><i class="fa fa-circle-o"></i> FCL
                      <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                      </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('invoice-tarif-nct-index')}}">Data Tarif NCT1</a></li>
                        <li><a href="{{route('invoice-release-nct-index')}}">Data Release/Gate Out</a></li>
                        <li><a href="{{route('invoice-nct-index')}}">Data Invoice</a></li>
                    </ul>
                </li>-->
            </ul>
        </li>
        <li class="treeview">
            <a href="#">
              <i class="fa fa-users"></i>
              <span>Users</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="{{route('user-index')}}">User Lists</a></li>
                <li><a href="{{route('role-index')}}">Roles</a></li>
                <li><a href="{{route('permission-index')}}">Permissions</a></li>
            </ul>
        </li>
    </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

<script>
    $('.sidebar-menu ul li').find('a').each(function () {
        var link = new RegExp($(this).attr('href')); //Check if some menu compares inside your the browsers link
        if (link.test(document.location.href)) {
            if(!$(this).parents().hasClass('active')){
                $(this).parents('li').addClass('menu-open');
                $(this).parents().addClass("active");
                $(this).addClass("active"); //Add this too
            }
        }
    });
</script>