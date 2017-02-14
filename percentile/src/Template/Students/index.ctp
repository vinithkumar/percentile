<?php echo $this->Html->css('layout'); ?>
<?php echo $this->Html->script('jquery-1.10.2.min'); ?>
<?php echo $this->Html->script('jquery.form.min'); ?>


<script type="text/javascript">
$(document).ready(function() {
    var options = {
            target:   '#output',   // target element(s) to be updated with server response
            beforeSubmit:  beforeSubmit,  // pre-submit callback
            success:       afterSuccess,  // post-submit callback
            uploadProgress: OnProgress, //upload progress callback
            resetForm: true        // reset the form after successful submit
        };

     $('#MyUploadForm').submit(function() {
            $(this).ajaxSubmit(options);
            // always return false to prevent standard browser submit and page navigation
            return false;
        });

$("#data_selection1, #data_selection2").change(function () {
        var radioanswer = 'none';
if ($('input[name=data_selection]:checked').val() != null) {
   radioanswer = $('input[name=data_selection]:checked').val();
   if(radioanswer == 1)
   {
    $('#sample_data').hide();
    $('#custom_data').show();
    $('#sampleoutput').hide();
    $('#output').show();
    $("#output").html("<span style='font-size:18px;padding-left: 280px;'>Please upload only .txt file.</span>");
   } else if(radioanswer == 0)
   {
    $('#sample_data').show();
    $('#custom_data').hide();
    $('#sampleoutput').show();
    $('#output').hide();
   }
}
});
//function after succesful file upload (when server response)
function afterSuccess()
{
    $('#submit-btn').show(); //hide submit button
    $('#loading-img').hide(); //hide submit button
    $('#progressbox').delay( 1000 ).fadeOut(); //hide progress bar

}

//function to check file size before uploading.
function beforeSubmit(){
    //check whether browser fully supports all File API
   if (window.File && window.FileReader && window.FileList && window.Blob)
    {

        if( !$('#FileInput').val()) //check empty input filed
        {
            $("#output").html("Are you kidding me?");
            return false
        }

        var fsize = $('#FileInput')[0].files[0].size; //get file size
        var ftype = $('#FileInput')[0].files[0].type; // get file type

        //Allowed file size is less than 5 MB (1048576)
        if(fsize>5242880)
        {
            $("#output").html("<b>"+bytesToSize(fsize) +"</b> Too big file! <br />File is too big, it should be less than 5 MB.");
            return false
        }

        $('#submit-btn').hide(); //hide submit button
        $('#loading-img').show(); //hide submit button
        $("#output").html("");
    }
    else
    {
        //Output error to older unsupported browsers that doesn't support HTML5 File API
        $("#output").html("Please upgrade your browser, because your current browser lacks some new features we need!");
        return false;
    }
}

//progress bar function
function OnProgress(event, position, total, percentComplete)
{
    //Progress bar
    $('#progressbox').show();
    $('#progressbar').width(percentComplete + '%') //update progressbar percent complete
    $('#statustxt').html(percentComplete + '%'); //update status text
    if(percentComplete>50)
        {
            $('#statustxt').css('color','#000'); //change status text to white after 50%
        }
}

//function to format bites bit.ly/19yoIPO
function bytesToSize(bytes) {
   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
   if (bytes == 0) return '0 Bytes';
   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}

});

</script>
<?php echo $this->Html->css('style'); ?>

<div style="width:100%height:100%">
  <div style="width:100%">
      <h3>Student's Percentile Rank</h3>
  </div>
  <div style="background-color:#ACC2E9; height:540px;width:30%;float:left;">

     <div style="display: inline-block;padding: 20px 5px 5px 30px;"><input type="radio" checked="checked" value="0" id="data_selection1" name="data_selection">&nbsp;Sample Data</div>
     <div style="display: inline-block;padding: 20px 5px 5px 30px;"><input type="radio" value="1" id="data_selection2" name="data_selection">&nbsp;Custom Data</div>

     <div id="sample_data">
     <div style="padding-left: 35px;"><h4>Input :</h4></div>
     <div style="padding-left: 40px;overflow-y: scroll;
    overflow-x: hidden;
    height: 440px;">
     <?php if($students != '') {
            foreach ($studentData as $key => $value) {
               echo utf8_encode($value)."<br>";
            }
           } else {
             echo "Unknown Input Data";
           }
     ?>
     </div>
     </div>
     <div id="custom_data" style="display:none;">
            <div id="upload-wrapper">
            <div align="center">

            <form action="uploadStudentsDetails" method="post" enctype="multipart/form-data" id="MyUploadForm">
            <input name="FileInput" id="FileInput" type="file" accept="text/plain"/>
            <input type="submit"  id="submit-btn" value="Upload" />
            <img src="<?php WWW_ROOT.'DS'; ?>images/ajax-loader.gif" id="loading-img" style="display:none;padding-top: 10px;" alt="Please Wait"/>
            </form>
            <div id="progressbox" ><div id="progressbar"></div ><div id="statustxt">0%</div></div>
            </div>
            </div>
     </div>
  </div>
  <div style="background-color:#eee; height:520px;width:70%;float:left;overflow: scroll;">
    <div id="sampleoutput">
            <div class="divTable" style="width: 100%;" >
            <?php if($students != '') { ?>
            <div class="divTableBody">
            <div class="divTableRow">
            <div class="divTableCell"><strong>Name</strong></div>
            <div class="divTableCell"><strong>GPA</strong></div>
            <div class="divTableCell"><strong>Percentile Rank</strong></div>
            </div>
            <?php foreach($students as $val) { ?>
                <div class="divTableRow">
                <div class="divTableCell"><?php echo $val['name'];?></div>
                <div class="divTableCell"><?php echo utf8_encode($val['gpa']);?></div>
                <div class="divTableCell"><?php echo $val['percentile'];?></div>
                </div>
            <?php } ?>
            </div>
            <?php } ?>
            </div>
    </div>
    <div id="output">
    </div>
            <!-- DivTable.com -->
  </div>


</div>
