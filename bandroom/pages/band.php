<?php
require_once("../db.php");

// 每页显示的数量
$per_page = 10;

// 初始化变量
$whereClause = "WHERE is_deleted=0"; // 默认查询条件
$orderClause = "ORDER BY id ASC";    // 默认排序
$limitClause = "";                   // 分页
$p = isset($_GET["p"]) ? max((int)$_GET["p"], 1) : 1; // 当前页码
$start_item = ($p - 1) * $per_page;

// 搜索功能
if (!empty($_GET["search"])) {
  $search = $conn->real_escape_string($_GET["search"]);
  $whereClause .= " AND band.name LIKE '%$search%'";
}

if (!empty($_GET["area"])) {
  $area_id = (int)$_GET["area"];
  $whereClause .= " AND band.area_id = $area_id";
}

// 排序
if (isset($_GET["order"]) && in_array($_GET["order"], [1, 2])) {
  $order = (int)$_GET["order"];
  if ($order == 1) {
    $orderClause = "ORDER BY id ASC";
  } elseif ($order == 2) {
    $orderClause = "ORDER BY id DESC";
  }
}


// 分页
$total_sql = "SELECT COUNT(*) as total FROM band $whereClause";
$result_total = $conn->query($total_sql);
$total_data = $result_total->fetch_assoc();
$total_count = $total_data["total"];
$total_page = ceil($total_count / $per_page);

$limitClause = "LIMIT $start_item, $per_page";

$p = $_GET["p"] ?? 1; // 如果 $_GET["p"] 不存在，默認為 1

// 最终查询
$sql = "SELECT band.*, area.name AS area_name 
        FROM band 
        JOIN area ON band.area_id = area.id 
        $whereClause 
        $orderClause 
        $limitClause";
$result = $conn->query($sql);
$rows = $result->fetch_all(MYSQLI_ASSOC);

// 获取所有区域（用于区域筛选）
$areasql = "SELECT * FROM area";
$resultCate = $conn->query($areasql);
$areas = $resultCate->fetch_all(MYSQLI_ASSOC);
?>



<!--
=========================================================
* Material Dashboard 3 - v3.2.0
=========================================================

* Product Page: https://www.creative-tim.com/product/material-dashboard
* Copyright 2024 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Material Dashboard 3 by Creative Tim
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
    integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show  bg-gray-100">
<?php include("../../sidebar.php") ?>

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl" id="navbarBlur"
      data-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <div class="d-flex align-itmems-center">
              <?php if (isset($_GET["search"])): ?>
                <a class="btn btn=primary " href="band.php"><i class="fa-solid fa-circle-left fa-fw"></i></a>
              <?php endif ?>
              <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
              <li class="breadcrumb-item text-sm text-dark active" aria-current="page">練團室租借管理</li>
            </div>
          </ol>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <form action="" method="get">
              <div class="input-group input-group-outline link-white">
                <label class="form-label"></label>
                <input type="search" name="search" class="form-control" value="<?= $_GET["search"] ?? "" ?>">
              </div>
            </form>
          </div>
          <ul class="navbar-nav d-flex align-items-center  justify-content-end">

            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </a>
            </li>
            <li class="nav-item px-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0">
                <i class="material-symbols-rounded fixed-plugin-button-nav">settings</i>
              </a>
            </li>
            <li class="nav-item dropdown pe-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class="material-symbols-rounded">notifications</i>
              </a>
              <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                <li class="mb-2">
                  <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex py-1">
                      <div class="my-auto">
                        <img src="../assets/img/team-2.jpg" class="avatar avatar-sm  me-3 ">
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                          <span class="font-weight-bold">New message</span> from Laur
                        </h6>
                        <p class="text-xs text-secondary mb-0">
                          <i class="fa fa-clock me-1"></i>
                          13 minutes ago
                        </p>
                      </div>
                    </div>
                  </a>
                </li>
                <li class="mb-2">
                  <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex py-1">
                      <div class="my-auto">
                        <img src="../assets/img/small-logos/logo-spotify.svg"
                          class="avatar avatar-sm bg-gradient-dark  me-3 ">
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                          <span class="font-weight-bold">New album</span> by Travis Scott
                        </h6>
                        <p class="text-xs text-secondary mb-0">
                          <i class="fa fa-clock me-1"></i>
                          1 day
                        </p>
                      </div>
                    </div>
                  </a>
                </li>
                <li>
                  <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex py-1">
                      <div class="avatar avatar-sm bg-gradient-secondary  me-3  my-auto">
                        <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1"
                          xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                          <title>credit-card</title>
                          <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                              <g transform="translate(1716.000000, 291.000000)">
                                <g transform="translate(453.000000, 454.000000)">
                                  <path class="color-background"
                                    d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z"
                                    opacity="0.593633743"></path>
                                  <path class="color-background"
                                    d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z">
                                  </path>
                                </g>
                              </g>
                            </g>
                          </g>
                        </svg>
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                          Payment successfully completed
                        </h6>
                        <p class="text-xs text-secondary mb-0">
                          <i class="fa fa-clock me-1"></i>
                          2 days
                        </p>
                      </div>
                    </div>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item d-flex align-items-center">
              <a href="../pages/sign-in.html" class="nav-link text-body font-weight-bold px-0">
                <i class="material-symbols-rounded">account_circle</i>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-2">
      <div class="row">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-dark shadow-dark border-radius-lg pt-3 pb-2 d-flex justify-content-between">
                <div class="px-2">
                  <h4 class="text-white fw-normal ps-3 fs-4">練團室租借管理</h4>
                  <h6 class=" text-capitalize ps-3 fw-normal text-secondary">共計 <?= $total_count ?> 使用者</h6>
                </div>
                <div class="py-2">
                  <a href="band_upload.php" class="btn btn-dark  btn-outline-light text-white mx-3 fs-6" title="新稱使用者">新增場地<i class="fa-solid fa-plus"></i></a>
                </div>


              </div>
              <div class="d-flex justify-content-between">
                <ul class="nav nav-underline">
                  <li class="nav-item">
                    <a class="nav-link text-dark <?php if (!isset($_GET["area"])) echo "active" ?>" aria-current="page" href="band.php">全部</a>
                  </li>
                  <?php foreach ($areas as $area): ?>

                    <li class="nav-item">
                      <a class="nav-link text-dark 
                    <?php
                    if (
                      isset($_GET["area"])
                      && $_GET["area"] == $area["id"]
                    ) echo "active";
                    ?>"
                        href="band.php?area=<?= $area["id"] ?>">
                        <?= $area["name"] ?>
                      </a>
                    </li>
                  <?php endforeach; ?>
                </ul>

                <div class="py-2">
                  <?php
                  $order = $_GET["order"] ?? "";
                  ?>
                  <div class="btn-group">
                    <a class="btn btn-light " href="band.php?p=<?= $p ?>&order=1"><i class="fa-solid fa-arrow-up-1-9"></i></a>
                    <a class="btn btn-light" href="band.php?p=<?= $p ?>&order=2"><i class="fa-solid fa-arrow-down-1-9"></i></i></a>
                  </div>
                </div>

              </div>
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary fs-5 font-weight-bolder opacity-7">編碼</th>
                      <th class="text-uppercase text-secondary fs-5 font-weight-bolder opacity-7 ps-2">所在地域</th>
                      <th class="text-uppercase text-secondary fs-5 font-weight-bolder opacity-7 ps-2">區域分店</th>
                      <th class="text-center text-uppercase text-secondary fs-5 font-weight-bolder opacity-7">資訊</th>
                      <th class=" text-uppercase text-secondary fs-5 font-weight-bolder opacity-7">價格</th>
                      <th class="text-secondary opacity-7"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($rows as $user): ?>
                      <tr>
                        <td><?= $user["id"] ?></td>
                        <td><a href="band_porduct.php?id=<?= $user["id"] ?>"><?= $user["name"] ?></a></td>
                        <td><?= $user["area"] ?></td>
                        <td><?= $user["information"] ?></td>
                        <td><?= $user["price"] ?></td>
                        <td class="ps-2">
                          <a href="band_porduct.php?id=<?= $user["id"] ?>"><i class="fa-solid fa-eye"></i></a>
                          <a href="band_user-edit.php?id=<?= $user["id"] ?>"><i class="fa-solid fa-pen-to-square fa-fw"></i></a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <div class="py-2 d-flex justify-content-between">

              </div>

            </div>
          </div>
        </div>
      </div>
      <!--分頁 -->
      <?php if ($total_count > 0): ?> <!-- 如果有记录显示分页 -->
        <nav aria-label="Page navigation example bg-dark" class="d-flex justify-content-center">
          <ul class="pagination">
            <?php for ($i = 1; $i <= $total_page; $i++): ?>
              <li class="page-item <?php if ($i == $p) echo "active"; ?>">

                <a style="border-color: transparent;"
                  class="page-link <?php echo ($i == $p) ? 'bg-gradient-dark text-white' : 'bg-white text-dark'; ?>"
                  href="band.php?p=<?= $i ?>&search=<?= isset($_GET['search']) ? urlencode($_GET['search']) : '' ?>&area=<?= isset($_GET['area']) ? $_GET['area'] : '' ?>&order=<?= isset($_GET['order']) ? $_GET['order'] : '' ?>">
                  <?= $i ?>
                </a>





              </li>
            <?php endfor; ?>
          </ul>
        </nav>

      <?php else: ?>
        <div class="py-2">目前沒有使用者</div>
      <?php endif; ?>
    </div>
  </main>
  <div class="fixed-plugin">

    <div class="card shadow-lg">
      <div class="card-header pb-0 pt-3">
        <div class="float-start">
          <h5 class="mt-3 mb-0">Material UI Configurator</h5>
          <p>See our dashboard options.</p>
        </div>
        <div class="float-end mt-4">
          <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
            <i class="material-symbols-rounded">clear</i>
          </button>
        </div>
        <!-- End Toggle Button -->
      </div>
      <hr class="horizontal dark my-1">
      <div class="card-body pt-sm-3 pt-0">
        <!-- Sidebar Backgrounds -->
        <div>
          <h6 class="mb-0">Sidebar Colors</h6>
        </div>
        <a href="javascript:void(0)" class="switch-trigger background-color">
          <div class="badge-colors my-2 text-start">
            <span class="badge filter bg-gradient-primary" data-color="primary" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-dark active" data-color="dark" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-info" data-color="info" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-success" data-color="success" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-warning" data-color="warning" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-danger" data-color="danger" onclick="sidebarColor(this)"></span>
          </div>
        </a>
        <!-- Sidenav Type -->
        <div class="mt-3">
          <h6 class="mb-0">Sidenav Type</h6>
          <p class="text-sm">Choose between different sidenav types.</p>
        </div>
        <div class="d-flex">
          <button class="btn bg-gradient-dark px-3 mb-2" data-class="bg-gradient-dark"
            onclick="sidebarType(this)">Dark</button>
          <button class="btn bg-gradient-dark px-3 mb-2 ms-2" data-class="bg-transparent"
            onclick="sidebarType(this)">Transparent</button>
          <button class="btn bg-gradient-dark px-3 mb-2  active ms-2" data-class="bg-white"
            onclick="sidebarType(this)">White</button>
        </div>
        <p class="text-sm d-xl-none d-block mt-2">You can change the sidenav type just on desktop view.</p>
        <!-- Navbar Fixed -->
        <div class="mt-3 d-flex">
          <h6 class="mb-0">Navbar Fixed</h6>
          <div class="form-check form-switch ps-0 ms-auto my-auto">
            <input class="form-check-input mt-1 ms-auto" type="checkbox" id="navbarFixed" onclick="navbarFixed(this)">
          </div>
        </div>
        <hr class="horizontal dark my-3">
        <div class="mt-2 d-flex">
          <h6 class="mb-0">Light / Dark</h6>
          <div class="form-check form-switch ps-0 ms-auto my-auto">
            <input class="form-check-input mt-1 ms-auto" type="checkbox" id="dark-version" onclick="darkMode(this)">
          </div>
        </div>
        <hr class="horizontal dark my-sm-4">
        <a class="btn bg-gradient-info w-100" href="https://www.creative-tim.com/product/material-dashboard-pro">Free
          Download</a>
        <a class="btn btn-outline-dark w-100"
          href="https://www.creative-tim.com/learning-lab/bootstrap/overview/material-dashboard">View documentation</a>
        <div class="w-100 text-center">
          <a class="github-button" href="https://github.com/creativetimofficial/material-dashboard"
            data-icon="octicon-star" data-size="large" data-show-count="true"
            aria-label="Star creativetimofficial/material-dashboard on GitHub">Star</a>
          <h6 class="mt-3">Thank you for sharing!</h6>
          <a href="https://twitter.com/intent/tweet?text=Check%20Material%20UI%20Dashboard%20made%20by%20%40CreativeTim%20%23webdesign%20%23dashboard%20%23bootstrap5&amp;url=https%3A%2F%2Fwww.creative-tim.com%2Fproduct%2Fsoft-ui-dashboard"
            class="btn btn-dark mb-0 me-2" target="_blank">
            <i class="fab fa-twitter me-1" aria-hidden="true"></i> Tweet
          </a>
          <a href="https://www.facebook.com/sharer/sharer.php?u=https://www.creative-tim.com/product/material-dashboard"
            class="btn btn-dark mb-0 me-2" target="_blank">
            <i class="fab fa-facebook-square me-1" aria-hidden="true"></i> Share
          </a>
        </div>
      </div>
    </div>
  </div>
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>
</body>

</html>