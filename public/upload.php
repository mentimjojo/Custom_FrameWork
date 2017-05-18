<form method="post" enctype="multipart/form-data">
    <?php
    if(isset($_POST['submitOne'])){
        // Upload
        Utils::$files->upload->setUploadPath('upload');
        Utils::$files->upload->setMaxSize(100);
        Utils::$files->upload->setMaxFiles(1);
        Utils::$files->upload->setFileTypes(array('pdf', 'jpg', 'png'));
        Utils::$files->upload->setMultiple(false);
        $up = Utils::$files->upload->send($_FILES['file']);

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
        Utils::$files->upload->setUploadPath('upload');
        Utils::$files->upload->setMaxSize(100);
        Utils::$files->upload->setMaxFiles(5);
        Utils::$files->upload->setFileTypes(array('pdf', 'jpg', 'png'));
        Utils::$files->upload->setMultiple(true);
        //$up = Utils::$files->upload->send($_FILES['files']);

        var_dump(count($_FILES['files']['name']));
        //var_dump($up);
    }
    ?>
    Multiple
    <input type="file" name="files[]" multiple id="fileToUpload">
    <input type="submit" name="submitMultiple" value="Upload Image">
</form>