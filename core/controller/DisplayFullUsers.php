<?php
class DisplayFullUsers{
    protected $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * displayUsers - Displays the users database table in
     * a nicely formatted html table.
     */
   public function displayUsers() {
         $acc = new GetCodeDeEncrypt();
        $site = 1;

        $query = $this->conn->prepare(
            "SELECT SECURE_HASH,SECURE_TOKEN FROM site_security WHERE site=?"
        );
        $query->bind_param("i", $site);
        $query->execute();
        $result = $query->get_result();
        $query->close();
        $secure = $result->fetch_assoc();
        $stoken = $secure['SECURE_TOKEN'];
        $shash = $secure['SECURE_HASH'];

        $q = "SELECT username, email, level, timestamp FROM uverify ORDER BY level DESC";
        $result = $this->conn->query($q);
        /* Error occurred, return given name by default */
        $num_rows = $result->num_rows;

        if (!$result || ($num_rows < 0)) {
            echo "Information display error";
            return;
        }
        if ($num_rows === 0) {
            echo "Empty database table";
            return;
        }
        /* Display table contents */
        echo "<table class='table' id='display'>";
        echo "<tr class='title'><td colspan='2'>Username</td>"
        . "<td>Level</td>"
        . "<td colspan='2'>Email</td>"
        . "<td colspan='2'>Last activity</td></tr>";

        while ($fecth_rows = $result->fetch_array()) {
            $uname = $acc->ende_crypter(
                "decrypt",
                $fecth_rows["username"],
                $stoken,
                $shash
            );

            $ulevel = $fecth_rows["level"];
            $email = $acc->ende_crypter(
                "decrypt",
                $fecth_rows["email"],
                $stoken,
                $shash
            );

            $time = $fecth_rows["timestamp"];

            echo "<tr><td colspan='2'>" . $uname . "</td><td>" . $ulevel . "</td><td colspan='2'>" . $email . "</td><td colspan='2'>" . $time . "</td></tr>";
        }
        echo "</table>";
    }

    /**
     * displayBannedUsers - Displays the banned users
     * database table in a nicely formatted html table.
     */
    public function displayBannedUsers() {

        $q = "SELECT user_id,banned_timestamp FROM banned_users ORDER BY user_id";
        $result = $this->conn->query($q);
        /* Error occurred, return given name by default */
        $num_rows = $result->num_rows;
        if (!$result || ($num_rows < 0)) {
            echo " Error de visualización de información";
            return;
        }
        if ($num_rows == 0) {
            echo "<p class='col-12'>There are no banned users.</p>";
            return;
        }
        /* Display table contents */
        echo "<div class='table-responsive'>";
        echo "<table class='table table-sm' id='display'>";
        echo "<tr class='title'><tr colspan='2'>Usuario</td><td colspan='2'>Tiempo Prohibido</td></tr>";
        while ($fecth_rows = $result->fetch_array()) {
            $uname = $fecth_rows["user_id"];
            $time = $fecth_rows["banned_timestamp"];

            echo "<tr><td colspan='2'>" . $uname . "</td><td colspan='2'>" . $time . "</td></tr>";
        }
        echo "</table>";
        echo "</div>";
    }


}
