<?php 
    include('../incld/header.php');
?>
<div class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 my-5">
                <div class="card my-5">
                    <div class="card-header bg-light">
                        <h5>Login Form</h5>
                    </div>
                    <div class="card-body">
                        <div class=row">
                            <div class="col-md-12">
                                <?php include('../incld/messages.php'); ?>
                            </div>
                        </div>
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="">User ID</label>
                                <input type="text" name="user_id" class="form-control" placeholder="User ID">
                            </div>
                            <div class="form-group">
                                <label for="">Password</label>
                                <input type="password" name="pass_wd" class="form-control" placeholder="Password">
                            </div>
                            <div class="form-group">
                                <button type="submit" name="submit" class="btn btn-primary btn-block" >Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div>
<?php
    include('../incld/jslib.php');
    include('../incld/footer.php');
?>