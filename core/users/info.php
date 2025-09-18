<div class="container-fluid">
 <div class="row">
  <div class="col-md-12 mr-auto">
<div class="card card-primary card-outline">
 <div class="card-body">
  <a name="about">
<h2>Connected</h2>
  </a>
  <div class="descText text-center">
<h4>Wellcome to dashboard panel</h4>
<hr class="colorgraph">
<p>
 You are connected as <strong><?php echo $_SESSION['levels']; ?></strong> 
</p>
  </div>
 </div>
</div>
  </div>
 </div>
</div>
<div class="container-fluid">
 <div class="row">
  <div class="col-md-3">

<!-- Profile Image -->
<div class="card card-primary card-outline">
 <div class="card-body box-profile">
  <div class="text-center">
<img class="profile-user-img img-fluid img-circle"
  src="<?php echo SITE_PATH; ?>uploads/user.jpg"
  alt="User profile picture">
  </div>

  <h3 class="profile-username text-center"><?php echo $_SESSION["username"]; ?></h3>

 </div>
 <!-- /.card-body -->
</div>
<!-- /.card -->

<!-- About Me Box -->
<div class="card card-primary">
 <div class="card-header">
  <h3 class="card-title">About Me</h3>
 </div>
 <!-- /.card-header -->
 <div class="card-body">
  <strong><i class="fas fa-book mr-1"></i> Education</strong>

  <hr>

  <strong><i class="fas fa-pencil-alt mr-1"></i> Skills</strong>

  <hr>

  <strong><i class="far fa-file-alt mr-1"></i> Notes</strong>


 </div>
 <!-- /.card-body -->
</div>
<!-- /.card -->
  </div>
  <!-- /.col -->
  <div class="col-md-9">
<div class="card">
 <div class="card-header p-2">
  <ul class="nav nav-tabs" id="users" role="tablist">
<li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Activity</a></li>
<li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Timeline</a></li>
<li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
  </ul>
 </div><!-- /.card-header -->
 <div class="card-body">
  <div class="tab-content">
<div class="active tab-pane" id="activity">
 <!-- Post -->
 <div class="post">
     <p>Activity</p>
 </div>
 <!-- /.post -->
</div>
<!-- /.tab-pane -->
<div class="tab-pane" id="timeline">
 <!-- The timeline -->
 <div class="post">
  <!-- timeline time label -->
  <p>Timeline</p>
 </div>
</div>
<!-- /.tab-pane -->

<div class="tab-pane" id="settings">
 <div class="post">
<p>Settings</p>
 </div>

</div>
<!-- /.tab-pane -->
  </div>
  <!-- /.tab-content -->
 </div><!-- /.card-body -->
</div>
          <script>
        $(document).ready(function() {  
            
            $(".tab-pane").hide(); //Hide all content
            $("ul.nav-tabs li:first").addClass("active").show(); //Activate first tab
            $(".tab-pane:first").show(); //Show first tab content
                                       
            $("ul.nav-tabs li").click(function() {
                           
    		$("ul.nav-tabs li").find("a").removeClass("active"); //Remove any "active" class
                $(this).find("a").addClass("active"); //Add "active" class to selected tab
                $(this).show();    		
    		$(".tab-pane").hide(); //Hide all tab content
                //Find the href attribute value to identify the active tab + content
    		var activeTab = $(this).find("a").attr("href");                                
                $(activeTab).addClass("active").show();                
    		$(activeTab).fadeIn(); //Fade in the active ID content                
    		return false;
            });
        });
        
    </script>
<!-- /.nav-tabs-custom -->
  </div>
  <!-- /.col -->
 </div>
 <!-- /.row -->
</div>
