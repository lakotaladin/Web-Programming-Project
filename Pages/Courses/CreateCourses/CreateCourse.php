<?php
session_start();
include_once '../../../Shared/connection.php';
error_reporting(E_ERROR | E_WARNING | E_PARSE);
if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $opis = $_POST["description"];
    if (isset($_SESSION["UserName"]) == true) {
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];

            // File properties
            $file_name = $file['name'];
            $file_tmp = $file['tmp_name'];
            $file_size = $file['size'];
            $file_error = $file['error'];

            // Work out the file extension
            $file_ext = explode('.', $file_name);
            $file_ext = strtolower(end($file_ext));


            //  DOZVOLJENI FOMRATI KURSA - FAJL
            $allowed = array('txt', 'pdf', 'doc', 'docx', 'ppt', 'pptx');

            if (in_array($file_ext, $allowed)) {
                if ($file_error === 0) {
                    if ($file_size <= 2097152) {

                        $file_name_new = uniqid('', true) . '.' . $file_ext;
                        $file_destination = 'uploads/' . $file_name_new;

                        if (move_uploaded_file($file_tmp, $file_destination)) {
                            $userName = $_SESSION["UserName"];
                            $sql = "INSERT INTO kurs (Naziv,Opis,Created,Kreator,file_name,file_path) VALUES('$name','$opis',false,'$userName','$file_name', '$file_destination')";
                            if ($con->query($sql) == true) {
                                $sql2 = "SELECT * FROM kurs WHERE ('$name'= Naziv AND '$userName'= Kreator)";
                                $result = $con->query($sql2);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<script>alert("Uspešno ste dodali kurs!")</script>';
                                        echo '<script>window.location.href="./DodajPitanja.php?IdKursa=' . $row['Id']  . '";</script>';
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                echo '<script>alert("Niste uneli ispravan format fajla, dozvoljeni fajlovi su .txt, .pdf, .doc, .docx")</script>';
                echo '<script>window.location.href="/WebProgramiranje/Pages/Courses/CreateCourses/CreateCourse.php";</script>';
            }
        }
    }
}





?>

<style>
    <?php
    include 'CreateCourse.css'
    ?>
</style>
<?php include '../../../Components/Navbar/Navbar.php' ?>

<div class='marginica'>
    <form enctype="multipart/form-data" method="POST">
        <div class="form-container">
            <h1 class="form-heading">Upišite podatke kursa</h1>
            <hr />
            <br>
            <div class="form-field">
                <label for="course-name">Ime kursa:</label>
                <input type="text" id="course-name" placeholder="Upišite ime kursa ovde..." name="name" required>
            </div>
            <div class="form-field">
                <label for="course-name">Deskripcija kursa:</label>
                <input type="text" id="course-name" placeholder="Opis kursa..." name="description" required>
            </div>
            <label for="course-name"><b>Dozvoljeni formati (.txt, .pdf, .doc, .docx, .pptx)</b></label><br><br>
            <input type="file" accept="txt, pdf, doc, docx, ppt, pptx" name="file"><br><br>
            <hr />
            <br>
            <br>
            <input type="submit" name="submit" class="form-submit" value="Potvrdi" />

        </div>
    </form>
</div>

<?php include '../../../Components/Footer/footer.php' ?>