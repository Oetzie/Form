<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<base href="[[++site_url]]" />

		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,600,700" type="text/css" />
	</head>
	<body style="margin: 0; padding: 15px; background: #ffffff;">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: 'Open Sans', Arial, Verdana, sans-serif; font-size: 14px; line-height: 22px; font-weight: 400; color: #333333; background: #ffffff;">
			<tr>
				<td width="100%" align="left">
					<h2 style="font-size: 22px; font-weight: 600; line-height: 32px; margin: 0 0 10px;">Test formulier ingevuld</h2>
					<p>Het test formulier op de website <a href="[[++site_url? &scheme=`full`]]">[[++site_name]]</a> is door [[+form.name]] ingevuld. Onderstaand alle gegevens:<p> 
					<table cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="600" valign="top" align="left">
					
								<table cellpadding="4" cellspacing="0" border="0">
								    <tr>
										<td valign="top" align="left">Geslacht</td>
										<td valign="top" align="left">[[+form.sex:FormValue=`{"male": "Man", "female": "Vrouw"}`]]</td>
									</tr>
									<tr>
										<td valign="top" align="left">Naam</td>
										<td valign="top" align="left">[[+form.name]]</td>
									</tr>
									<tr>
										<td valign="top" align="left">E-mailadres</td>
										<td valign="top" align="left">[[+form.email]]</td>
									</tr>
									<tr>
										<td valign="top" align="left">Telefoonnummer</td>
										<td valign="top" align="left">[[+form.phone]]</td>
									</tr>
									<tr>
										<td valign="top" align="left">Type</td>
										<td valign="top" align="left">[[+form.type:FormValue=`{"phone": "Telefoon", "email": "E-mail", "fax": "Fax"}`]]</td>
									</tr>
									<tr>
										<td valign="top" align="left">Reactie en/of opmerking</td>
										<td valign="top" align="left">[[+form.content:nl2br]]</td>
									</tr>
								</table>
							
							</td>
						</tr>
					</table>
					<p>Met vriendelijke groet,</p>
					<p>[[++site_name]]</p>
				</td>
			</tr>
		</table>
	</body>
</html>