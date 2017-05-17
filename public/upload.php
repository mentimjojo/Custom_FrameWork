<form method="post" enctype="multipart/form-data">
    <?php
    if(isset($_POST['submitOne'])){
        // Upload
        Utils::$upload->setUploadPath('upload');
        Utils::$upload->setMaxSize(100);
        Utils::$upload->setMaxFiles(1);
        Utils::$upload->setFileTypes(array('pdf', 'jpg', 'png'));
        Utils::$upload->setMultiple(false);
        $up = Utils::$upload->send($_FILES['file']);

        var_dump($up);
    }
    ?>
    One
    <input type="file" name="file" id="fileToUpload">
    <input type="submit" name="submitOne" value="Upload Image">

    <br/><br/><br/>
    <?php
    if(isset($_POST['submitMultiple'])){
        // Upload
        Utils::$upload->setUploadPath('upload');
        Utils::$upload->setMaxSize(100);
        Utils::$upload->setMaxFiles(5);
        Utils::$upload->setFileTypes(array('pdf', 'jpg', 'png'));
        Utils::$upload->setMultiple(true);
        $up = Utils::$upload->send($_FILES['files']);

        var_dump($up);
    }
    ?>
    Multiple
    <input type="file" name="files[]" multiple id="fileToUpload">
    <input type="submit" name="submitMultiple" value="Upload Image">
</form>