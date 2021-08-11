<?php
$user = $_SESSION['user_id'];
$hash = $_SESSION['hash'];
$row = $conn->query("SELECT * FROM uverify WHERE iduv='$user' AND mkhash='$hash'")->fetch_assoc();
$rpro = $conn->query("SELECT * FROM profiles WHERE idp='$user' AND mkhash='$hash'")->fetch_assoc();
?>
<div class="container">
    <div class="row pt-2">
        <?php if (!empty($_SESSION['SuccessMessage'])) { ?>
            <div class="alert alert-success alert-container" id="alert">
                <strong><?php echo htmlentities($_SESSION['SuccessMessage']) ?></strong>
                <?php unset($_SESSION['SuccessMessage']); ?>
            </div>
        <?php } ?>
        <?php if (!empty($_SESSION['ErrorMessage'])) { ?>
            <div class="alert alert-danger alert-container" id="alert">
                <strong><?php echo htmlentities($_SESSION['ErrorMessage']) ?></strong>
                <?php unset($_SESSION['ErrorMessage']); ?>
            </div>
        <?php }; ?>
        <?php if (!empty($_SESSION['AlertMessage'])) { ?>
            <div class="alert alert-danger alert-container" id="alert">
                <strong><center><?php echo htmlentities($_SESSION['AlertMessage']) ?></center></strong>                                       
            </div>
        <?php } ?>
    </div>
</div>
<div class="container">
    <div class="row pt-2">        
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="profile.php" method="post" role="form" id="add_info">
                        <div class="mb-3">
                            <label class="form-label" for="username">Username:</label> 
                            <input type="text" class="form-control" id="username" name="username" value="<?php $row['username'] ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="email">Email:</label> 
                            <input type="text" class="form-control" id="email" name="email" value="<?php $row['email'] ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="firstname">Firstname:</label> 
                            <input type="text" class="form-control" id="firstname" name="firstname">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="lastname">Lastname:</label> 
                            <input type="text" class="form-control" id="lastname" name="lastname">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="phone">Phone:</label> 
                            <input type="text" class="form-control" id="phone" name="phone">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="address">Address:</label> 
                            <input type="text" class="form-control" id="address" name="address">
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
                            <input type="text" class="form-control" id="age" name="age">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="birthday">Birthday:</label> 
                            <input type="text" data-date-format="dd/mm/yyyy" class="form-control" id="birthday" name="birthday">
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
                            <label class="form-label" for="active">Active:</label> 
                            <input type="text" class="form-control" id="active" name="active">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="banned">Banned:</label> 
                            <input type="text" class="form-control" id="banned" name="banned">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="date">Date:</label> 
                            <input type="text" data-date-format="dd/mm/yyyy" class="form-control" id="date" name="date">
                        </div>
                        <script type="text/javascript">
                            $(document).ready(function ()
                            {
                                $("#date").datepicker({
                                    weekStart: 1,
                                    daysOfWeekHighlighted: "6,0",
                                    autoclose: true,
                                    todayHighlight: true
                                });
                                $("#date").datepicker("setDate", new Date());
                            });
                        </script>
                        <div class="mb-3">
                            <button type="submit" id="addrow" name="addrow" class="btn btn-primary">
                                <span class="glyphicon glyphicon-plus" onclick="dVals();"></span>
                                Add
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
