<h2>Test formulier</h2>
[[+form.error]]
<p class="info">Velden gemarkeerd met een sterretje zijn verplicht.</p>
<form action="[[~[[*id]]]]" method="post" name="test" class="form [[+form.submit:notempty=`form-active`]]">
    <div class="form-element [[+form.error.sex:notempty=`error`]]">
		<label for="sex">Uw geslacht</label>
		<div class="form-element-container">
			<select name="sex" id="sex">
			    <option value="male" [[+form.sex:FormIsSelected=`male`]]>Man</option>
			    <option value="female" [[+form.sex:FormIsSelected=`female`]]>Vrouw</option>
			</select> [[+form.error.sex]]
		</div>
	</div>
	<div class="form-element [[+form.error.name:notempty=`error`]]">
		<label for="name">Uw naam</label>
		<div class="form-element-container">
			<input type="text" name="name" id="name" value="[[+form.name]]" /> [[+form.error.name]]
		</div>
	</div>
	<div class="form-element [[+form.error.email:notempty=`error`]]">
		<label for="email">Uw e-mailadres</label>
		<div class="form-element-container">
			<input type="text" name="email" id="email" value="[[+form.email]]" /> [[+form.error.email]]
		</div>
	</div>
	<div class="form-element [[+form.error.phone:notempty=`error`]]">
		<label for="phone">Telefoonnummer</label>
		<div class="form-element-container">
			<input type="text" name="phone" id="phone" value="[[+form.phone]]" /> [[+form.error.phone]]
		</div>
	</div>
	<div class="form-element [[+form.error.type:notempty=`error`]]">
		<label for="type">Contact via</label>
		<div class="form-element-container">
			<label for="type1"><input type="checkbox" name="type[]" id="type1" value="phone" [[+form.type:FormIsChecked=`phone`]] /> Telefoon</label>
			<label for="type2"><input type="checkbox" name="type[]" id="type2" value="email" [[+form.type:FormIsChecked=`email`]] /> E-mail</label>
			<label for="type3"><input type="checkbox" name="type[]" id="type3" value="fax" [[+form.type:FormIsChecked=`fax`]] /> Fax</label>
			[[+form.error.type]]
		</div>
	</div>
	<div class="form-element [[+form.error.content:notempty=`error`]]">
		<label for="content">Uw reactie en/of opmerking</label>
		<div class="form-element-container">
			<textarea name="content" id="content">[[+form.content]]</textarea> [[+form.error.content]]
		</div>
	</div>
	<div class="form-element [[+form.error.recaptcha:notempty=`error`]]">
	    <div class="form-element-container">
		    [[+form.extensions.recaptcha]]
	    </div>
	</div>
	<div class="form-element">
		<div class="form-element-container">
			<button type="submit" name="submit" title="Versturen">Versturen</button>
		</div>
	</div>
</form>