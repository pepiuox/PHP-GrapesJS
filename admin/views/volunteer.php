<div class="container">
	<div class="row">
		<div class="card py-3">
			<div class="card-body">
				<form method="post" class="form-horizontal" role="form" id="add_volunteer" enctype="multipart/form-data">
					<div class="form-group">
						<label for="firstname">Firstname:</label>
						<input type="text" class="form-control" id="firstname" name="firstname">
					</div>
					<div class="form-group">
						<label for="lastname">Lastname:</label>
						<input type="text" class="form-control" id="lastname" name="lastname">
					</div>
					<div class="form-group">
						<label for="gender">Gender:</label>
						<select type="text" class="form-select" id="gender" name="gender" >
							<option value="Woman">Woman</option>
							<option value="Male">Male</option>
						</select>
					</div>
					<div class="form-group">
						<label for="birthday">Birthday:</label>
						<input type="text" data-date-format="dd/mm/yyyy" class="form-control" id="birthday" name="birthday">
					</div>
					<script type="text/javascript">
						$(document).ready(function ()
						{
							$("#birthday").datepicker({
								weekStart: 1,
								daysOfWeekHighlighted: "6,0",
								autoclose: true,
								todayHighlight: true
							});
							$("#birthday").datepicker("setDate", new Date());
						});
					</script>
					<div class="form-group">
						<label for="age">Age:</label>
						<input type="text" class="form-control" id="age" name="age">
					</div>
					<div class="form-group">
						<label for="phone">Phone:</label>
						<input type="text" class="form-control" id="phone" name="phone">
					</div>
					<div class="form-group">
						<label for="email">Email:</label>
						<input type="text" class="form-control" id="email" name="email">
					</div>
					<div class="form-group">
						<label for="social_media">Social media:</label>
						<input type="text" class="form-control" id="social_media" name="social_media">
					</div>
					<div class="form-group">
						<label for="web_blog">Web blog:</label>
						<input type="text" class="form-control" id="web_blog" name="web_blog">
					</div>
					<div class="form-group">
						<label for="address">Address:</label>
						<input type="text" class="form-control" id="address" name="address">
					</div>
					<div class="form-group">
						<label for="address_line_2">Address line 2:</label>
						<input type="text" class="form-control" id="address_line_2" name="address_line_2">
					</div>
					<div class="form-group">
						<label for="city">City:</label>
						<input type="text" class="form-control" id="city" name="city">
					</div>
					<div class="form-group">
						<label for="state_province_region">State province region:</label>
						<input type="text" class="form-control" id="state_province_region" name="state_province_region">
					</div>
					<div class="form-group">
						<label for="zip_code">Zip code:</label>
						<input type="text" class="form-control" id="zip_code" name="zip_code">
					</div>
					<div class="form-group">
						<label for="country">Country:</label>
						<input type="text" class="form-control" id="country" name="country">
					</div>
					<div class="form-group">
						<label for="profession">Profession:</label>
						<input type="text" class="form-control" id="profession" name="profession">
					</div>
					<div class="form-group">
						<label for="personal_interest">Personal interest:</label>
						<input type="text" class="form-control" id="personal_interest" name="personal_interest">
					</div>
					<div class="form-group">
						<label for="skills">Skills:</label>
						<input type="text" class="form-control" id="skills" name="skills">
					</div>
					<div class="form-group">
						<label for="allergies">Allergies:</label>
						<input type="text" class="form-control" id="allergies" name="allergies">
					</div>
					<div class="form-group">
						<label for="allergy_description">Allergy description:</label>
						<textarea type="text" class="form-control" id="allergy_description" name="allergy_description"></textarea>
					</div>
					<div class="form-group">
						<label for="diseases">Diseases:</label>
						<input type="text" class="form-control" id="diseases" name="diseases">
					</div>
					<div class="form-group">
						<label for="disease_description">Disease description:</label>
						<textarea type="text" class="form-control" id="disease_description" name="disease_description"></textarea>
					</div>
					<div class="form-group">
						<label for="comments">Comments:</label>
						<input type="text" class="form-control" id="comments" name="comments">
					</div>
					<div class="form-group">
						<label for="contact_person_name">Contact person name:</label>
						<input type="text" class="form-control" id="contact_person_name" name="contact_person_name">
					</div>
					<div class="form-group">
						<label for="contact_person_phone">Contact person phone:</label>
						<input type="text" class="form-control" id="contact_person_phone" name="contact_person_phone">
					</div>
					<div class="form-group">
						<label for="contact_person_email">Contact person email:</label>
						<input type="text" class="form-control" id="contact_person_email" name="contact_person_email">
					</div>
					<div class="form-group">
						<button type="submit" id="addrow" name="addrow" class="btn btn-primary"><span class="fas fa-plus-square" onclick="dVals();"></span> Add</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
