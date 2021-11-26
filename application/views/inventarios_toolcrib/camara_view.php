<!doctype html>
<html lang="en-US" xmlns:fb="https://www.facebook.com/2008/fbml" xmlns:addthis="https://www.addthis.com/help/api-spec"  prefix="og: http://ogp.me/ns#" class="no-js">
<head>
        <meta charset="utf-8">
        <title><?php if ($nombre_Empresa != "") { echo $nombre_Empresa; }?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link href='http://fonts.googleapis.com/css?family=Economica' rel='stylesheet' type='text/css'>
        <!-- Bootstrap -->
        <link href="<?php echo base_url();?>css/bootstrap.css" rel="stylesheet">
    
    
    
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<script>
	  (adsbygoogle = window.adsbygoogle || []).push({
		google_ad_client: "ca-pub-6724419004010752",
		enable_page_level_ads: true
	  });
	</script>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-131906273-1"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());
	  gtag('config', 'UA-131906273-1');
	</script>
</head>

<body>
	<div class="container-fluid">
            <div class="row" style="margin-top: -45px;">
                <div class="col-sm-12">
                    <center>
                    <a href="#">
                        <br><br>
                        <img class="img-responsive" src="<?php echo base_url(); ?>/images/WEWIRELOGO.png" alt="Logo de la Empresa" /></a>
                    </center>
                    <h3 style="text-align: center; margin-top: -30px;margin-left: 40px;color:#666666">Maintenance Manager</h3>
                    
                    <h4 style="text-align: center; margin-left: 20px;margin-top: 10px; color:#666666;font-weight: bold;">Captura de Qr de la pieza.</h4>
                </div>
            </div>
        </div>
	<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-3">
				<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
				<script>
				(adsbygoogle = window.adsbygoogle || []).push({});
				</script>
			</div>
			<div class="col-md-6">
				<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
				<div class="col-sm-12">
					<video id="preview" class="p-1 border" style="width:100%;"></video>
				</div>
				<script type="text/javascript">
					var scanner = new Instascan.Scanner({ video: document.getElementById('preview'), scanPeriod: 5, mirror: false });
					scanner.addListener('scan',function(content){
						alert("Lectura correcta");
                                                window.location.replace('https://192.168.98.200/cmpv1_0/index.php/inventariotoolcrib_controller/bajaFromQR/' + content);
					});
					Instascan.Camera.getCameras().then(function (cameras){
						if(cameras.length>0){
							scanner.start(cameras[1]);
							$('[name="options"]').on('change',function(){
								if($(this).val()==1){
									if(cameras[0]!=""){
										scanner.start(cameras[0]);
									}else{
										alert('No Front camera found!');
									}
								}else if($(this).val()==2){
									if(cameras[1]!=""){
										scanner.start(cameras[1]);
									}else{
										alert('No Back camera found!');
									}
								}
							});
						}else{
							console.error('No cameras found.');
							alert('No cameras found.');
						}
					}).catch(function(e){
						console.error(e);
						alert(e);
					});
				</script>
				<div class="btn-group btn-group-toggle mb-5" data-toggle="buttons">
                                    <table>
                                        <tr>
                                            <td>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </td>
                                            <td>
                                                <label class="btn btn-primary active">
                                                    <input type="radio" name="options" value="1" autocomplete="off" checked> Front Camera
                                                </label>
                                                <label class="btn btn-success">
                                                      <input type="radio" name="options" value="2" autocomplete="off"> Back Camera
                                                </label>
                                            </td>
                                        </tr>
                                    </table>
				</div>
			</div>
			
			
			<div class="col-sm-3">
				<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
				<!-- demo left sidebar -->
				<ins class="adsbygoogle"
					 style="display:block"
					 data-ad-client="ca-pub-6724419004010752"
					 data-ad-slot="7706376079"
					 data-ad-format="auto"
					 data-full-width-responsive="true"></ins>
				<script>
				(adsbygoogle = window.adsbygoogle || []).push({});
				</script>
			</div>
		
		</div>
	</div>
	
</body>
</html>
