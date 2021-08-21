<?php
$userid = $_SESSION['user_id'];
$hash = $_SESSION['hash'];

$respro = $conn->prepare("SELECT * FROM profiles WHERE idp = ? AND mkhash = ? ");
$respro->bind_param("ss", $userid, $hash);
$respro->execute();
//fetching result would go here, but will be covered later
$prof = $respro->get_result();
$rpro = $prof->fetch_assoc();
if (isset($_POST['update'])) {
    $firstname = protect($_POST['firstname']);
    $lastname = protect($_POST['lastname']);
    $up1 = $conn->prepare("UPDATE profiles SET firstname = ?, lastname = ? WHERE idp = ? AND mkhash = ?");
    $up1->bind_param("ssss", $firstname, $lastname, $userid, $hash);
    $up1->execute();
    $inst1 = $up1->affected_rows;
    $up1->close();
}
?>

<div class="container">
    <div class="row pt-2">        
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="post" role="form" >

                        <div class="mb-3">
                            <label class="form-label" for="firstname">Firstname:</label> 
                            <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo $rpro['firstname']; ?>"> 
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="lastname">Lastname:</label> 
                            <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $rpro['lastname']; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="phone">Phone:</label> 
                            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $rpro['phone']; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="address">Address:</label> 
                            <input type="text" class="form-control" id="address" name="address" value="<?php echo$rpro['address']; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="gender">Gender:</label> 
                            <select type="text" class="form-select" id="gender" name="gender">
                                <option value="Woman">Woman</option>
                                <option value="Male">Male</option>
                                <option value="I have doubts">I have doubts</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="age">Age:</label> 
                            <input type="text" class="form-control" id="age" name="age" value="<?php echo$rpro['age']; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="birthday">Birthday:</label> 
                            <input type="text" data-date-format="dd/mm/yyyy" class="form-control" id="birthday" name="birthday" value="<?php echo$rpro['birthday']; ?>">
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
                        <div class="mb-3">
                            <button type="submit" id="update" name="update" class="btn btn-primary">
                                <i class="fas fa-user-edit"></i> Update info
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
