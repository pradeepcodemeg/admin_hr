<!DOCTYPE html>
<html>
<head>
	<title>Certificate</title>
	<style type="text/css">
		body{
			font-weight: 300;
		}
	</style>
</head>
<body>
	<!-- <div  style="width: 100%; border: 5px solid #404899; border-radius: 4px; padding: 10px; background: url({{asset('public/images/frame.png')}}); background-repeat: no-repeat; background-size: cover; text-align: center;"> -->
	<div style="padding: 0px; position: relative;">
		<img src="{{asset('public/images/frame.png')}}" width="100%;">
		<table style="width: 100%; font-family: serif; margin: 0 auto; position: absolute; top: 40px; left: 50%; transform: translateX(-50%); z-index: 555;">
			<tbody>
				<tr>
					<td>
						<table style="width: 100%; text-align: center; margin: 0 auto; padding: 0;">
							<thead>
								<tr>
									<td>
										<h2 style="margin: 0; font-size: 20px; font-style: italic;">R.G.U.S. Inc.,</h2>
									</td>
								</tr>
								<tr>
									<td>
										<h3 style="font-size: 14px; text-transform: uppercase; font-weight: 500; margin: 0; font-style: italic;">home care agency</h3>
									</td>
								</tr>
							</thead>

							<tbody>
								<tr>
									<td>
										<h1 style="text-transform: uppercase; font-size: 30px; margin: 10px 0;">certificate</h1>
										<p style="margin: 0; font-weight: 800; font-size: 15px; text-transform: uppercase; font-style: italic;">of completion as hereby granted to:</p>
									</td>
								</tr>

								<tr>
									<td>
										<h2 style="font-size: 25px; margin: 5px 0 21px; text-transform: uppercase; font-style: italic; padding: 10px 0;">{{$data->firstname}} {{$data->lastname}}</h2>

										<p style="font-size: 15px; font-style: italic; margin: 5px 0;">For completing {{Carbon\Carbon::parse($data->credit_hours)->format('h:m')}} credit hour(s) of 2020 In-Service Training </p>
										<p style="font-size: 15px; font-style: italic; margin: 5px 0;">Subject Material: {{$data->training_name}}</p>
										<p style="font-size: 15px; margin: 5px 0;">Date: {{$data->passing_date}}</p>
									</td>
								</tr>

								<tr>
									<td style="position: relative; padding: 20px 0 0;">
										<p style="border-top: 1px solid #000; width: 200px; margin: 0 auto; text-align: center; font-size: 14px; padding-top: 2px;">Training Administrator</p>
										<img style="width: 100px; position: absolute; top: -15px; left: 50%; transform: translateX(-50%);" src="{{asset('public/images/sign.png')}}">
									</td>
								</tr>

								<tr>
									<td>
										<h5 style="text-align: center; text-transform: uppercase; font-size: 14px; margin: 10px 0;">authorized signature</h5>
									</td>
								</tr>

								<tr>
									<td style="text-align: right; padding-right: 50px;">
<<<<<<< HEAD
										<p style="margin: 0; font-size: 12px;font-weight: 300;">Source: MediFesta HealthCare Training</p>
										<p style="margin: 0; font-size: 12px;font-weight: 300;">A Devision of Health Care Training System</p>
=======
										<p style="margin: 0; font-size: 14px;">Source: MediFesta HealthCare Training</p>
										<p style="margin: 0; font-size: 14px;">A Devision of Health Care Training System</p>
>>>>>>> a9a6b9247d3761b10c718110b82f607a8c1a1485
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</body>
</html>