<?php
//This is temporal file only for add new row
if (isset($_POST['editrow'])) { 
$usercode = $_POST["usercode"]; 
$firstname = $_POST["firstname"]; 
$lastname = $_POST["lastname"]; 
$gender = $_POST["gender"]; 
$age = $_POST["age"]; 
$birthday = $_POST["birthday"]; 
$phone = $_POST["phone"]; 
$country = $_POST["country"]; 
$state = $_POST["state"]; 
$city = $_POST["city"]; 
$address1 = $_POST["address1"]; 
$address2 = $_POST["address2"]; 
$is_active = $_POST["is_active"]; 
$created = $_POST["created"]; 
$updated = $_POST["updated"]; 

$query = "UPDATE users_info SET usercode = ?, firstname = ?, lastname = ?, gender = ?, age = ?, birthday = ?, phone = ?, country = ?, state = ?, city = ?, address1 = ?, address2 = ?, is_active = ?, created = ?, updated = ? WHERE userid = ?";
$updated = $this->conn->prepare($sql);
$updated->bind_param('ssssisssssssissi', $usercode, $firstname, $lastname, $gender, $age, $birthday, $phone, $country, $state, $city, $address1, $address2, $is_active, $created, $updated, $id );
$updated->execute();
$updated->close();
}
?> 
<form role="form" id="edit_users_info" method="POST">
<div class="form-group">
                       <label for="usercode" class ="control-label col-md-6">Usercode:</label>
                       <input type="text" class="form-control" id="usercode" name="usercode" value="AArQneduR#63y$MH5)uBMG%oIdyDtt$&e8QUSA}4|nU2d$N(0D#sr7rU95203017">
                  </div>
<div class="form-group">
                       <label for="firstname" class ="control-label col-md-6">Firstname:</label>
                       <input type="text" class="form-control" id="firstname" name="firstname" value="Jose">
                  </div>
<div class="form-group">
                       <label for="lastname" class ="control-label col-md-6">Lastname:</label>
                       <input type="text" class="form-control" id="lastname" name="lastname" value="Mantilla">
                  </div>
<div class="form-group">
                       <label for="gender" class ="control-label col-md-6">Gender:</label>
                       <select class="form-select" id="gender" name="gender" >
<option value="Woman">Woman</option>
<option value="Male" selected>Male</option>
<option value="With doubt">With doubt</option>
</select>
</div>
<div class="form-group">
				<label for="age" class ="control-label col-md-6">Age:</label> <input type="text"
					class="form-control" id="age" name="age"
					value="48">
			</div>
			
<div class="form-group">
                       <label for="birthday" class ="control-label col-md-6">Birthday:</label>
                       <input type="text" data-date-format="dd/mm/yyyy" class="form-control" id="birthday" name="birthday" value="1975-06-30">
                  </div>
<script type="text/javascript">
                                        $(document).ready(function ()
                                        {
                                            $("#birthday").datepicker({
                                                weekStart: 1,
                                                daysOfWeekHighlighted: "6,0",
                                                autoclose: true,
                                                todayHighlight: true
                                            });
                                            $("#birthday").datepicker("setDate", new Date());
                                        });
                                    </script>
<div class="form-group">
                       <label for="phone" class ="control-label col-md-6">Phone:</label>
                       <input type="text" class="form-control" id="phone" name="phone" value="">
                  </div>
<div class="form-group">
                       <label for="country" class ="control-label col-md-6">Country:</label>
                       <input type="text" class="form-control" id="country" name="country" value="">
                  </div>
<div class="form-group">
                       <label for="state" class ="control-label col-md-6">State:</label>
                       <input type="text" class="form-control" id="state" name="state" value="">
                  </div>
<div class="form-group">
                       <label for="city" class ="control-label col-md-6">City:</label>
                       <input type="text" class="form-control" id="city" name="city" value="">
                  </div>
<div class="form-group">
                       <label for="address1" class ="control-label col-md-6">Address1:</label>
                       <input type="text" class="form-control" id="address1" name="address1" value="">
                  </div>
<div class="form-group">
                       <label for="address2" class ="control-label col-md-6">Address2:</label>
                       <input type="text" class="form-control" id="address2" name="address2" value="">
                  </div>
<div class="form-group">
				<label for="is_active" class ="control-label col-md-6">Is active:</label> <input type="text"
					class="form-control" id="is_active" name="is_active"
					value="">
			</div>
			
<div class="form-group">
                       <label for="created" class ="control-label col-md-6">Created:</label>
                       <input type="text" data-date-format="dd/mm/yyyy" class="form-control" id="created" name="created" value="2023-10-20 23:32:17">
                  </div>
<script type="text/javascript">
                                        $(document).ready(function ()
                                        {
                                            $("#created").datepicker({
                                                weekStart: 1,
                                                daysOfWeekHighlighted: "6,0",
                                                autoclose: true,
                                                todayHighlight: true
                                            });
                                            $("#created").datepicker("setDate", new Date());
                                        });
                                    </script>
<div class="form-group">
                       <label for="updated" class ="control-label col-md-6">Updated:</label>
                       <input type="text" data-date-format="dd/mm/yyyy" class="form-control" id="updated" name="updated" value="2023-10-22 03:10:40">
                  </div>
<script type="text/javascript">
                                        $(document).ready(function ()
                                        {
                                            $("#updated").datepicker({
                                                weekStart: 1,
                                                daysOfWeekHighlighted: "6,0",
                                                autoclose: true,
                                                todayHighlight: true
                                            });
                                            $("#updated").datepicker("setDate", new Date());
                                        });
                                    </script>
<div class="form-group">
        <button type="submit" id="editrow" name="editrow" class="btn btn-primary"><span class = "fas fa-edit"></span> Edit</button>
    </div>
</form>
