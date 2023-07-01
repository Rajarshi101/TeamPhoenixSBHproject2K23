<?php 
  session_start(); 
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['email']);
  	header("location: index.php");
  }

    $insert = false;
    $update = false;
    $delete = false;
    // Connect to the Database 
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "examsked";

    // Create a connection
    $conn = mysqli_connect($servername, $username, $password, $database);

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $subject = $_POST["Subject"];
        
          // Sql query to be executed
          $sql = "INSERT INTO `bookings` (`Subject`) VALUES ('$subject')";
          $result = mysqli_query($conn, $sql);
        
           
          if($result){ 
              $insert = true;
          }
          else{
              echo "The record was not inserted successfully because of this error ---> ". mysqli_error($conn);
          } 
    }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="UTF-8">
        <title>Notice Board</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
    integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');
            
            *{
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Poppins', sans-serif;
                scroll-behavior: smooth;
            }

            header {
                background-color: #0d423b;
                height: 110px;
            }

            nav{
                position: sticky;
                left: 0;
                top: 0;
                width: 100%;
                height: 75px;
                background: #54948b;
                box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
                z-index: 1;
            }

            nav .navbar{
                display: flex;
                align-items: center;
                justify-content: space-between;
                height: 100%;
                max-width: 100%;
                background: #54948b;
                margin: auto;
                padding: 1.5rem 1rem;
            }

            nav .navbar .logo a{
                color: #fff;
                font-size: 27px;
                font-weight: 600;
                text-decoration: none;
            }

            nav .navbar .menu{
                display: flex;
            }

            .navbar .menu li{
                list-style: none;
                margin: 0 15px;
            }

            .navbar .menu li a{
                color: #fff;
                font-size: 17px;
                font-weight: 500;
                text-decoration: none;
            }

            .register {
                position: absolute;
                right: 30px;
            }

            button {
                cursor: pointer;
                border: 0;
                border-radius: 5px;
                font-weight: 600;
                width: 110px;
                padding: 5px 0;
                transition: 0.4s;
            }

            .reg {
                color: rgb(104, 85, 224);
                background-color: rgba(255, 255, 255, 1);
                border: 1px solid rgba(104, 85, 224, 1);
            }

            button:hover {
                color: white;
                box-shadow: 0 0 20px rgba(104, 85, 224, 0.6);
                background-color: rgba(104, 85, 224, 1);
            }

            section{
                display: flex;
                height: 130vh;
                width: 100%;
                align-items: center;
                justify-content: center;
                color: #96c7e8;
                font-size: 70px;
            }

            #Home{
                background: linear-gradient(to bottom right, rgba(84,58,183,1) 25%, rgba(0,172,193,1) 100%);
            }

            .content {
                position: absolute;
                top: 300px;
                left: 100px;
                width: 500px;
                padding: 0;
                
            }

            .content h2 {
                width: 400px;
                font-size: 30px;
                background: linear-gradient(to right, #C6FFDD, #FBD786, #f7797d);
                background-clip: text;
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            section .container {
                font-size: 15px;
                height: 300px;
                width: 820px;
                top: 400px;
                position: absolute;
                border: black;
            }

            footer {
                background-color: #000000f3;
                color: white;
                padding: 25px;
                align-items: center;
                height: 70px;
                width: 1519.2px;
                display: inline-flex;
                justify-content: center;
                font-size: 15px;
            }
            footer img{
                margin-left: 10px;
            }
        </style>
    </head>
    <body>
        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit this Note</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>
                <form action="/ExamSked/subjectschedule.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="snoEdit" id="snoEdit">
                    <div class="form-group">
                    <label for="title">Note Title</label>
                    <input type="text" class="form-control" id="titleEdit" name="titleEdit" aria-describedby="emailHelp">
                    </div>

                    <div class="form-group">
                    <label for="desc">Note Description</label>
                    <textarea class="form-control" id="descriptionEdit" name="descriptionEdit" rows="3"></textarea>
                    </div> 
                </div>
                <div class="modal-footer d-block mr-auto">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
                </form>
            </div>
            </div>
        </div>
        <header id="header">
            <img src="Media/applogo.png" alt="applogo">
        </header>
        <nav>
            <div class="navbar">
                <ul class="menu">
                <li><a href="profile.php">Profile</a></li>
                    <li><a href="examschedule.php">Exam Schedule</a></li>
                    <li><a href="subjectschedule.php">Subject Schedule</a></li>
                    <li><a href="noticeboard.php">Notice Board</a></li>
                    <li><a href="how2use.php">How To Use</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
                <div class="register">
                    <a href="profile.php?logout='1'"><button class="reg">Logout</button></a>
                </div>
            </div>
        </nav>
        <?php
  if($insert){
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Success!</strong> Your note has been inserted successfully
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
  }
  ?>
  <?php
  if($update){
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Success!</strong> Your note has been updated successfully
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
  }
  ?>
        <section id="Home">
            <div class="content">
                <h2><b><i>Subject Schedule Menu</i></b></h2>
            </div>
            <div class="container">
                <table class="table" id="myTable">
                <thead>
                    <tr>
                    <th scope="col">S.No.</th>
                    <th scope="col">ID</th>
                    <th scope="col">Exam Date</th>
                    <th scope="col">Subject</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $sql = "SELECT * FROM `bookings`";
                    $result = mysqli_query($conn, $sql);
                    $sno = 0;
                    while($row = mysqli_fetch_assoc($result)){
                        $sno = $sno + 1;
                        echo "<tr>
                        <th scope='row'>". $sno . "</th>
                        <td>". $row['id'] . "</td>
                        <td>". $row['booking_date'] . "</td>
                        <td><button class='edit btn btn-sm btn-primary' id=".$row['id'].">Add</button></td>
                        
                    </tr>";
                    } 
                    ?>
<!-- id=".$row['sno']." -->
<!-- id=d".$row['id']." -->
                </tbody>
                </table>
            </div>
            <hr>
            <!-- Optional JavaScript -->
            <!-- jQuery first, then Popper.js, then Bootstrap JS -->
            <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
                integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
                crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
                integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
                crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
                integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
                crossorigin="anonymous"></script>
            <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
            <script>
                $(document).ready(function () {
                $('#myTable').DataTable();

                });
            </script>
            <script>
                edits = document.getElementsByClassName('edit');
                Array.from(edits).forEach((element) => {
                element.addEventListener("click", (e) => {
                    console.log("edit ");
                    tr = e.target.parentNode.parentNode;
                    title = tr.getElementsByTagName("td")[0].innerText;
                    description = tr.getElementsByTagName("td")[1].innerText;
                    console.log(title, description);
                    titleEdit.value = title;
                    descriptionEdit.value = description;
                    idEdit.value = e.target.id;
                    console.log(e.target.id)
                    $('#editModal').modal('toggle');
                })
                })

            </script>
        </section>
        <footer>
            <p>Copyright © 2023. All rights reserved | Total Visits | Website Powered By</p><img src="Media/phoenix.png" height="40" width="40">
        </footer>
    </body>
</html>