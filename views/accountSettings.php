<?php
$user = $_SESSION['user_id'];
$hash = $_SESSION['hash'];


$rquery = $conn->query("SELECT * FROM uverify WHERE username='$user' AND mkhash='$hash'");

if ($rquery->num_rows > 0) {
    $row = $rquery->fetch_assoc();

    if (!empty($_SESSION['AlertMessage']) && !empty($_SESSION['RecoveryMessage'])) {
        $rm = $_SESSION['RecoveryMessage'];
        if ($rm === 1) {
            ?>
            <div class="container">
                <div class="row pt-2">
                    <?php if (!empty($_SESSION['ErrorMessage'])): ?>
                        <div class="alert alert-danger alert-container" id="alert">
                            <strong><?php echo htmlentities($_SESSION['ErrorMessage']) ?></strong>
                            <?php unset($_SESSION['ErrorMessage']); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($_SESSION['AlertMessage'])): ?>
                        <div class="alert alert-danger alert-container" id="alert">
                            <strong><center><?php echo htmlentities($_SESSION['AlertMessage']) ?></center></strong>                                       
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="container">
                <div class="row pt-2">
                    <form action="profile.php" method="post" role="form">
                        <h3 class="cnt">¿Crea tu frase de recuperación?</h3>
                        <hr class="colorgraph">
                        <p class="">Introduce tu frase de seguridad. Conserva en un lugar seguro esta frase ya que se te pedira en casos de seguridad.</p>
                        <label for="email">Ingrese su PIN<span class="red">*</span>:
                        </label> <input type="password" name="pin" id="pin"
                                        placeholder="PIN" class="input form-control" autocomplete="off"
                                        required autofocus><br>
                        <label for="email">Frase de recuperación<span class="red">*</span>:
                        </label> <input type="text" name="rvphrase" id="rvphrase"
                                        placeholder="Frase de recuperación" class="input form-control" autocomplete="off"
                                        required autofocus><br>
                        <input type="submit" name="makerecoveryphrase"
                               value="Guardar frase" class="btn btn-lg btn-block submit" />
                    </form>
                </div>
            </div>
            <?php
        }
    } else {
        ?>
        <div class="container">
            <div class="row pt-2">
                <?php if (!empty($_SESSION['SuccessMessage'])): ?>
                    <div class="alert alert-success alert-container" id="alert">
                        <strong><?php echo htmlentities($_SESSION['SuccessMessage']) ?></strong>
                        <?php unset($_SESSION['SuccessMessage']); ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($_SESSION['ErrorMessage'])): ?>
                    <div class="alert alert-danger alert-container" id="alert">
                        <strong><?php echo htmlentities($_SESSION['ErrorMessage']) ?></strong>
                        <?php unset($_SESSION['ErrorMessage']); ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($_SESSION['AlertMessage'])): ?>
                    <div class="alert alert-danger alert-container" id="alert">
                        <strong><center><?php echo htmlentities($_SESSION['AlertMessage']) ?></center></strong>                                       
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="container">
            <div class="row pt-2">        
                <div class="col-md-12">
                    <form action="profile.php" method="post" role="form" id="add_info">
                        <div class="form-group">
                            <label for="username">Username:</label> 
                            <input type="text" class="form-control" id="username" name="username" value="<?php $row['username'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label> 
                            <input type="text" class="form-control" id="email" name="email">
                        </div>
                        <div class="form-group">
                            <label for="firstname">Firstname:</label> 
                            <input type="text" class="form-control" id="firstname" name="firstname">
                        </div>
                        <div class="form-group">
                            <label for="lastname">Lastname:</label> 
                            <input type="text" class="form-control" id="lastname" name="lastname">
                        </div>
                        <div class="form-group">
                            <label for="phone">phone:</label> 
                            <input type="text" class="form-control" id="phone" name="phone">
                        </div>
                        <div class="form-group">
                            <label for="address">address:</label> 
                            <input type="text" class="form-control" id="address" name="address">
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender:</label> 
                            <select type="text" class="form-control" id="gender" name="gender">
                                <option value="Woman">Woman</option>
                                <option value="Male">Male</option>
                                <option value="No lo sabe">No lo sabe</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="age">Age:</label> 
                            <input type="text" class="form-control" id="age" name="age">
                        </div>
                        <div class="form-group">
                            <label for="birthday">Cumpleños:</label> 
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
                        <div class="form-group">
                            <label for="active">Active:</label> 
                            <input type="text" class="form-control" id="active" name="active">
                        </div>
                        <div class="form-group">
                            <label for="banned">Banned:</label> 
                            <input type="text" class="form-control" id="banned" name="banned">
                        </div>
                        <div class="form-group">
                            <label for="date">Date:</label> 
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
                        <div class="form-group">
                            <button type="submit" id="addrow" name="addrow" class="btn btn-primary">
                                <span class="glyphicon glyphicon-plus" onclick="dVals();"></span>
                                Add
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }
}
?>


