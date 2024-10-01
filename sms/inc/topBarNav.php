<nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-gradient sticky-top">
            <div class="container px-4 px-lg-5 ">
                <button class="navbar-toggler btn btn-sm" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <a class="navbar-brand" href="./">
                <img src="<?php echo validate_image($_settings->info('logo')) ?>" width="30" height="30" class="d-inline-block align-top" alt="" loading="lazy">
                <?php echo $_settings->info('short_name') ?>
                </a>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item"><a class="nav-link" aria-current="page" href="./">Home</a></li>
                        <?php if(isset($_SESSION['userdata']['id']) && $_settings->userdata('type') == 2): ?>

                        <li class="nav-item"><a class="nav-link" href="./?page=my_transactions">My Transactions</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link" href="./?page=about_us">About Us</a></li>
                    </ul>
                    <div class="navbar-nav ml-auto d-flex align-items-center">
                      <?php if(isset($_SESSION['userdata']['id']) && $_settings->userdata('type') == 2): ?>
                        <a href="./?page=update_account" class="text-light  nav-link"><b> Hi, <?php echo $_settings->userdata('firstname')?>!</b></a>
                            <a href="classes/Login.php?f=logout_user" class="text-light  nav-link"><i class="fa fa-sign-out-alt"></i></a>
                        <?php else: ?>
                        <a class="btn btn-dark text-light ml-2" id="login-btn" href="./login.php">Login</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
<script>
  $(function(){
    $('#navbarResponsive').on('show.bs.collapse', function () {
        $('#mainNav').addClass('navbar-shrink')
    })
    $('#navbarResponsive').on('hidden.bs.collapse', function () {
        if($('body').offset.top == 0)
          $('#mainNav').removeClass('navbar-shrink')
    })
  })

  $('#search-form').submit(function(e){
    e.preventDefault()
     var sTxt = $('[name="search"]').val()
     if(sTxt != '')
      location.href = './?p=products&search='+sTxt;
  })
</script>