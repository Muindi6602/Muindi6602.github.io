<?php require_once('./../../config.php') ?>
<?php 
 $qry = $conn->query("SELECT *,concat(lastname,', ',firstname,' ', middlename) as name FROM `users` where  id = '{$_GET['id']}' ");
 if($qry->num_rows > 0){
     foreach($qry->fetch_assoc() as $k => $v){
         $$k=$v;
     }
     $meta_qry = $conn->query("SELECT * FROM `user_meta` where user_id = '{$id}'");
     while($row = $meta_qry->fetch_assoc()){
         $meta[$row['meta_field']] = $row['meta_value'];
     }
    
 }
?>
   <style>
    #uni_modal .modal-footer{
        display:none;
    }
    img#cimg{
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
</style> 
<div class="container-fluid" id="print_out">
    <div id='transaction-printable-details' class='position-relative'>
        <div class="row">
            <fieldset class="w-100">
                <legend class="text-info">Information</legend>
                <div class="col-12">
                    <div class="form-group text-center">
                        <img src="<?php echo validate_image(isset($avatar) ? $avatar :'') ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
                    </div>
                    <hr class="border-light">
                    <dl>
                        <dt class="text-info">Name:</dt>
                        <dd class="pl-3"><?php echo $name ?></dd>
                        <dt class="text-info">Gender:</dt>
                        <dd class="pl-3"><?php echo isset($meta['gender']) ? $meta['gender'] : '' ?></dd>
                        <dt class="text-info">Date of Birth:</dt>
                        <dd class="pl-3"><?php echo isset($meta['dob']) ? date("M d, Y",strtotime($meta['dob'])) : '' ?></dd>
                        <dt class="text-info">Contact #:</dt>
                        <dd class="pl-3"><?php echo isset($meta['contact']) ? $meta['contact'] : '' ?></dd>
                        <dt class="text-info">Address:</dt>
                        <dd class="pl-3"><?php echo isset($meta['address']) ? $meta['address'] : '' ?></dd>
                        <dt class="text-info">Email:</dt>
                        <dd class="pl-3"><?php echo isset($username) ? $username : '' ?></dd>
                    </dl>
                </div>
            </fieldset>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="col-12">
        <div class="d-flex justify-content-end align-items-center">
            <button class="btn btn-light btn-flat" type="button" id="cancel" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>
    

<script>
    $(function(){
		$('.table td,.table th').addClass('py-1 px-2 align-middle')
        $('#print').click(function(){
            start_loader()
            var _el = $('<div>')
            var _head = $('head').clone()
                _head.find('title').text("Payment Details - Print View")
            var p = $('#print_out').clone()
            p.find('hr.border-light').removeClass('.border-light').addClass('border-dark')
            p.find('.btn').remove()
            _el.append(_head)
            _el.append('<div class="d-flex justify-content-center">'+
                      '<div class="col-1 text-right">'+
                      '<img src="<?php echo validate_image($_settings->info('logo')) ?>" width="65px" height="65px" />'+
                      '</div>'+
                      '<div class="col-10">'+
                      '<h4 class="text-center"><?php echo $_settings->info('name') ?></h4>'+
                      '<h4 class="text-center">Payment Details</h4>'+
                      '</div>'+
                      '<div class="col-1 text-right">'+
                      '</div>'+
                      '</div><hr/>')
            _el.append(p.html())
            var nw = window.open("","","width=1200,height=900,left=250,location=no,titlebar=yes")
                     nw.document.write(_el.html())
                     nw.document.close()
                     setTimeout(() => {
                         nw.print()
                         setTimeout(() => {
                            nw.close()
                            end_loader()
                         }, 200);
                     }, 500);

        })
    })
</script>