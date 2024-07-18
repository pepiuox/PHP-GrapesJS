
<style>#swpm-create-user input {position: relative;}</style>
    <form action="" method="post" name="swpm-create-user" id="swpm-create-user" class="validate swpm-validate-form">
        <input name="action" type="hidden" value="createuser">
        <input type="hidden" id="_wpnonce_create_swpmuser_admin_end" name="_wpnonce_create_swpmuser_admin_end" value="4d50da8c51"><input type="hidden" name="_wp_http_referer" value="/wp-admin/admin.php?page=simple_wp_membership&amp;member_action=add">        <h3>Add Member</h3>
        <p>Create a brand new user and add it to this site.</p>
        <table class="form-table">
            <tbody>
                <tr class="form-required swpm-admin-add-username">
                    <th scope="row"><label for="user_name">Username <span class="description">(required)</span></label></th>
                    <td><input class="regular-text validate[required,custom[noapostrophe],custom[SWPMUserName],minSize[4],ajax[ajaxUserCall]]" name="user_name" type="text" id="user_name" value="" aria-required="true"></td>
                </tr>
                <tr class="form-required swpm-admin-add-email">
                    <th scope="row"><label for="email">E-mail <span class="description">(required)</span></label></th>
                    <td><input name="email" autocomplete="off" class="regular-text validate[required,custom[email],ajax[ajaxEmailCall]]" type="text" id="email" value=""></td>
                </tr>
                <tr class="form-required swpm-admin-add-password">
                    <th scope="row"><label for="password">Password <span class="description">(twice, required)</span></label></th>
                    <td><input class="regular-text" name="password" type="password" id="pass1" autocomplete="off">
                        <br>
                        <input class="regular-text" name="password_re" type="password" id="pass2" autocomplete="off">
                        <br>
                        <div id="pass-strength-result">Strength indicator</div>
                        <p class="description indicator-hint">Hint: The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers and symbols like ! " ? $ % ^ &amp; ).</p>
                    </td>
                </tr>
                <tr class="swpm-admin-add-account-state">
                    <th scope="row"><label for="account_state">Account Status</label></th>
                    <td><select class="regular-text" name="account_state" id="account_state">
                            <option selected="selected" value="active"> Active</option><option value="inactive"> Inactive</option><option value="activation_required"> Activation Required</option><option value="pending"> Pending</option><option value="expired"> Expired</option>                        </select>
                    </td>
                </tr>
                <tr class="swpm-admin-edit-membership-level">
                    <th scope="row"><label for="membership_level">Membership Level</label></th>
                    <td><select class="regular-text" name="membership_level" id="membership_level">
                                                    </select>
                    </td>
                </tr>
                <tr class="swpm-admin-edit-access-starts">
    <th scope="row"><label for="subscription_starts">Access Starts </label></th>
    <td><input class="regular-text hasDatepicker" name="subscription_starts" type="text" id="subscription_starts" value="2024-06-15"></td>
</tr>
<tr class="swpm-admin-edit-first-name">
    <th scope="row"><label for="first_name">First Name </label></th>
    <td><input class="regular-text" name="first_name" type="text" id="first_name" value=""></td>
</tr>
<tr class="swpm-admin-edit-last-name">
    <th scope="row"><label for="last_name">Last Name </label></th>
    <td><input class="regular-text" name="last_name" type="text" id="last_name" value=""></td>
</tr>
<tr class="swpm-admin-edit-gender">
    <th scope="row"><label for="gender">Gender</label></th>
    <td><select class="regular-text" name="gender" id="gender">
            <option value="male">Male</option><option value="female">Female</option><option selected="selected" value="not specified">Not Specified</option>        </select>
    </td>
</tr>
<tr class="swpm-admin-edit-phone">
    <th scope="row"><label for="phone">Phone </label></th>
    <td><input class="regular-text" name="phone" type="text" id="phone" value=""></td>
</tr>
<tr class="swpm-admin-edit-address-street">
    <th scope="row"><label for="address_street">Street </label></th>
    <td><input class="regular-text" name="address_street" type="text" id="address_street" value=""></td>
</tr>
<tr class="swpm-admin-edit-address-city">
    <th scope="row"><label for="address_city">City </label></th>
    <td><input class="regular-text" name="address_city" type="text" id="address_city" value=""></td>
</tr>
<tr class="swpm-admin-edit-address-state">
    <th scope="row"><label for="address_state">State </label></th>
    <td><input class="regular-text" name="address_state" type="text" id="address_state" value=""></td>
</tr>
<tr class="swpm-admin-edit-address-zipcode">
    <th scope="row"><label for="address_zipcode">Zipcode </label></th>
    <td><input class="regular-text" name="address_zipcode" type="text" id="address_zipcode" value=""></td>
</tr>
<tr class="swpm-admin-edit-address-country">
    <th scope="row"><label for="country">Country </label></th>
    <td><select class="regular-text" id="country" name="country">
<option value="" selected="">(Please Select)</option>
<option value="Afghanistan">Afghanistan</option>
<option value="Albania">Albania</option>
<option value="Algeria">Algeria</option>
<option value="Andorra">Andorra</option>
<option value="Angola">Angola</option>
<option value="Antigua and Barbuda">Antigua and Barbuda</option>
<option value="Argentina">Argentina</option>
<option value="Armenia">Armenia</option>
<option value="Aruba">Aruba</option>
<option value="Australia">Australia</option>
<option value="Austria">Austria</option>
<option value="Azerbaijan">Azerbaijan</option>
<option value="Bahamas">Bahamas</option>
<option value="Bahrain">Bahrain</option>
<option value="Bangladesh">Bangladesh</option>
<option value="Barbados">Barbados</option>
<option value="Belarus">Belarus</option>
<option value="Belgium">Belgium</option>
<option value="Belize">Belize</option>
<option value="Benin">Benin</option>
<option value="Bhutan">Bhutan</option>
<option value="Bolivia">Bolivia</option>
<option value="Bonaire">Bonaire</option>
<option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
<option value="Botswana">Botswana</option>
<option value="Brazil">Brazil</option>
<option value="Brunei">Brunei</option>
<option value="Bulgaria">Bulgaria</option>
<option value="Burkina Faso">Burkina Faso</option>
<option value="Burundi">Burundi</option>
<option value="Cambodia">Cambodia</option>
<option value="Cameroon">Cameroon</option>
<option value="Canada">Canada</option>
<option value="Cape Verde">Cape Verde</option>
<option value="Cayman Islands">Cayman Islands</option>
<option value="Central African Republic">Central African Republic</option>
<option value="Chad">Chad</option>
<option value="Chile">Chile</option>
<option value="China">China</option>
<option value="Colombia">Colombia</option>
<option value="Comoros">Comoros</option>
<option value="Congo (Brazzaville)">Congo (Brazzaville)</option>
<option value="Congo">Congo</option>
<option value="Costa Rica">Costa Rica</option>
<option value="Cote d'Ivoire">Cote d'Ivoire</option>
<option value="Croatia">Croatia</option>
<option value="Cuba">Cuba</option>
<option value="Curacao">Curacao</option>
<option value="Cyprus">Cyprus</option>
<option value="Czech Republic">Czech Republic</option>
<option value="Denmark">Denmark</option>
<option value="Djibouti">Djibouti</option>
<option value="Dominica">Dominica</option>
<option value="Dominican Republic">Dominican Republic</option>
<option value="East Timor (Timor Timur)">East Timor (Timor Timur)</option>
<option value="Ecuador">Ecuador</option>
<option value="Egypt">Egypt</option>
<option value="El Salvador">El Salvador</option>
<option value="Equatorial Guinea">Equatorial Guinea</option>
<option value="Eritrea">Eritrea</option>
<option value="Estonia">Estonia</option>
<option value="Eswatini">Eswatini</option>
<option value="Ethiopia">Ethiopia</option>
<option value="Fiji">Fiji</option>
<option value="Finland">Finland</option>
<option value="France">France</option>
<option value="French Polynesia">French Polynesia</option>
<option value="Gabon">Gabon</option>
<option value="Gambia, The">Gambia, The</option>
<option value="Georgia">Georgia</option>
<option value="Germany">Germany</option>
<option value="Ghana">Ghana</option>
<option value="Greece">Greece</option>
<option value="Grenada">Grenada</option>
<option value="Guatemala">Guatemala</option>
<option value="Guinea">Guinea</option>
<option value="Guinea-Bissau">Guinea-Bissau</option>
<option value="Guyana">Guyana</option>
<option value="Haiti">Haiti</option>
<option value="Honduras">Honduras</option>
<option value="Hong Kong">Hong Kong</option>
<option value="Hungary">Hungary</option>
<option value="Iceland">Iceland</option>
<option value="India">India</option>
<option value="Indonesia">Indonesia</option>
<option value="Iran">Iran</option>
<option value="Iraq">Iraq</option>
<option value="Ireland">Ireland</option>
<option value="Israel">Israel</option>
<option value="Italy">Italy</option>
<option value="Jamaica">Jamaica</option>
<option value="Japan">Japan</option>
<option value="Jordan">Jordan</option>
<option value="Kazakhstan">Kazakhstan</option>
<option value="Kenya">Kenya</option>
<option value="Kiribati">Kiribati</option>
<option value="Korea, North">Korea, North</option>
<option value="Korea, South">Korea, South</option>
<option value="Kuwait">Kuwait</option>
<option value="Kyrgyzstan">Kyrgyzstan</option>
<option value="Laos">Laos</option>
<option value="Latvia">Latvia</option>
<option value="Lebanon">Lebanon</option>
<option value="Lesotho">Lesotho</option>
<option value="Liberia">Liberia</option>
<option value="Libya">Libya</option>
<option value="Liechtenstein">Liechtenstein</option>
<option value="Lithuania">Lithuania</option>
<option value="Luxembourg">Luxembourg</option>
<option value="Macedonia">Macedonia</option>
<option value="Madagascar">Madagascar</option>
<option value="Malawi">Malawi</option>
<option value="Malaysia">Malaysia</option>
<option value="Maldives">Maldives</option>
<option value="Mali">Mali</option>
<option value="Malta">Malta</option>
<option value="Marshall Islands">Marshall Islands</option>
<option value="Mauritania">Mauritania</option>
<option value="Mauritius">Mauritius</option>
<option value="Mexico">Mexico</option>
<option value="Micronesia">Micronesia</option>
<option value="Moldova">Moldova</option>
<option value="Monaco">Monaco</option>
<option value="Mongolia">Mongolia</option>
<option value="Montenegro">Montenegro</option>
<option value="Morocco">Morocco</option>
<option value="Mozambique">Mozambique</option>
<option value="Myanmar">Myanmar</option>
<option value="Namibia">Namibia</option>
<option value="Nauru">Nauru</option>
<option value="Nepa">Nepa</option>
<option value="Netherlands">Netherlands</option>
<option value="New Zealand">New Zealand</option>
<option value="Nicaragua">Nicaragua</option>
<option value="Niger">Niger</option>
<option value="Nigeria">Nigeria</option>
<option value="Norway">Norway</option>
<option value="Oman">Oman</option>
<option value="Pakistan">Pakistan</option>
<option value="Palau">Palau</option>
<option value="Palestine">Palestine</option>
<option value="Panama">Panama</option>
<option value="Papua New Guinea">Papua New Guinea</option>
<option value="Paraguay">Paraguay</option>
<option value="Peru">Peru</option>
<option value="Philippines">Philippines</option>
<option value="Poland">Poland</option>
<option value="Portugal">Portugal</option>
<option value="Qatar">Qatar</option>
<option value="Romania">Romania</option>
<option value="Russia">Russia</option>
<option value="Rwanda">Rwanda</option>
<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
<option value="Saint Lucia">Saint Lucia</option>
<option value="Saint Vincent">Saint Vincent</option>
<option value="Samoa">Samoa</option>
<option value="San Marino">San Marino</option>
<option value="Sao Tome and Principe">Sao Tome and Principe</option>
<option value="Saudi Arabia">Saudi Arabia</option>
<option value="Senegal">Senegal</option>
<option value="Serbia">Serbia</option>
<option value="Seychelles">Seychelles</option>
<option value="Sierra Leone">Sierra Leone</option>
<option value="Singapore">Singapore</option>
<option value="Slovakia">Slovakia</option>
<option value="Slovenia">Slovenia</option>
<option value="Solomon Islands">Solomon Islands</option>
<option value="Somalia">Somalia</option>
<option value="South Africa">South Africa</option>
<option value="Spain">Spain</option>
<option value="Sri Lanka">Sri Lanka</option>
<option value="Sudan">Sudan</option>
<option value="Suriname">Suriname</option>
<option value="Swaziland">Swaziland</option>
<option value="Sweden">Sweden</option>
<option value="Switzerland">Switzerland</option>
<option value="Syria">Syria</option>
<option value="Taiwan">Taiwan</option>
<option value="Tajikistan">Tajikistan</option>
<option value="Tanzania">Tanzania</option>
<option value="Thailand">Thailand</option>
<option value="Togo">Togo</option>
<option value="Tonga">Tonga</option>
<option value="Trinidad and Tobago">Trinidad and Tobago</option>
<option value="Tunisia">Tunisia</option>
<option value="Turkey">Turkey</option>
<option value="Turkmenistan">Turkmenistan</option>
<option value="Tuvalu">Tuvalu</option>
<option value="Uganda">Uganda</option>
<option value="Ukraine">Ukraine</option>
<option value="United Arab Emirates">United Arab Emirates</option>
<option value="United Kingdom">United Kingdom</option>
<option value="United States of America">United States of America</option>
<option value="Uruguay">Uruguay</option>
<option value="Uzbekistan">Uzbekistan</option>
<option value="Vanuatu">Vanuatu</option>
<option value="Vatican City">Vatican City</option>
<option value="Venezuela">Venezuela</option>
<option value="Vietnam">Vietnam</option>
<option value="Yemen">Yemen</option>
<option value="Zambia">Zambia</option>
<option value="Zimbabwe">Zimbabwe</option></select></td>
</tr>
<tr class="swpm-admin-edit-company">
    <th scope="row"><label for="company_name">Company</label></th>
    <td><input name="company_name" type="text" id="company_name" class="regular-text" value=""></td>
</tr>
<tr class="swpm-admin-edit-member-since">
    <th scope="row"><label for="member_since">Member Since </label></th>
    <td><input class="regular-text hasDatepicker" name="member_since" type="text" id="member_since" value="2024-06-15"></td>
</tr>
            </tbody>
        </table>
        <script>
    jQuery(document).ready(function ($) {
        $('#subscription_starts').datepicker({dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true, yearRange: "-100:+100"});
        $('#member_since').datepicker({dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true, yearRange: "-100:+100"});
        //Allow any field with class 'swpm-date-picker' to use the datepicker
        $('.swpm-date-picker').datepicker({dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true, yearRange: "-100:+100"});
    });
</script>
        <p class="submit"><input type="submit" name="createswpmuser" id="createswpmusersub" class="button button-primary" value="Add New Member "></p>    </form>
