<?php

//Creating a shortcode for register form
function lokis_loop_register_form()
{
    // if (is_user_logged_in()) {
    //     ob_start();
    //     $dashboard_page_id = (get_option('lokis_setting'))['dashboard'];
    //     $lokis_dashboard_page = get_permalink($dashboard_page_id);

    //     if (empty($lokis_dashboard_page)) {
    //         $lokis_dashboard_page = site_url();
    //         return $lokis_dashboard_page;
    //     }
    //     echo '<script>window.location.href = "' . $lokis_dashboard_page . '";</script>';
    // } else {
    ob_start();
    ?>

    <!-- creating game host application form -->
    <div class="lokis-gamehost-application-container">
        <form action="" class="lokis-gamehost-form" method="post">
            <?php wp_nonce_field(-1, 'loki_registration_nonce'); ?>

            <div id="lokis-names" class="lokis-formquestion-item">
                <label for="loki-name" class="lokisloop-label">
                    <?php _e('Name', 'lokis-loop'); ?> <span class="lokis-req-field">*</span>
                </label>
                <input aria label="single line text" maxlength="4000" class="lokis-input form-control" id='loki-name'>
            </div>

            <div id="lokis-email" class="lokis-formquestion-item">
                <label for="loki-email" class="lokisloop-label">
                    <?php _e('Email', 'lokis-loop'); ?> <span class="lokis-req-field">*</span>
                </label>
                <input aria label="single line text" maxlength="4000" class="lokis-input form-control" id='loki-email'>
            </div>

            <div id="lokis-organization" class="lokis-formquestion-item">
                <label for="loki-organization" class="lokisloop-label">
                    <?php _e('Organization Name ', 'lokis-loop'); ?><span class="lokis-req-field">*</span>
                </label>
                <input aria label="single line text" maxlength="4000" class="lokis-input form-control"
                    id='loki-organization'>
            </div>

            <div id="lokis-organization-type" class="lokis-formquestion-item">
                <label for="organizational_name" class="lokisloop-label">
                    <?php _e('What best describes your organization ', 'lokis-loop'); ?><span
                        class="lokis-req-field">*</span>

                </label>
                <div class="lokisloop-organization-wrapper">
                    <div class="lokisloop-selective-box">
                        <input type="radio" id="library" name="loki_organization_type" value="Public-Library">
                        <label for="library" class="lokisloop-label">
                            <?php _e('Public library', 'lokis-loop'); ?>
                        </label>
                    </div>

                    <div class="lokisloop-selective-box">
                        <input type="radio" id="other-library" name="loki_organization_type" value="other-library">
                        <label for="other-library" class="lokisloop-label">
                            <?php _e('Other library', 'lokis-loop'); ?>
                        </label>
                    </div>

                    <div class="lokisloop-selective-box">
                        <input type="radio" id="school" name="loki_organization_type" value="K-12 School">
                        <label for="school" class="lokisloop-label">
                            <?php _e('K-12 school', 'lokis-loop'); ?>
                        </label>
                    </div>

                    <div class="lokisloop-selective-box">
                        <input type="radio" id="University/College" name="loki_organization_type"
                            value="University/college">
                        <label for="University/college" class="lokisloop-label">
                            <?php _e('University/college', 'lokis-loop'); ?>
                        </label>
                    </div>

                    <div class="lokisloop-selective-box">
                        <input type="radio" id="other-organization" name="loki_organization_type" value="Non-profit">
                        <label for="other-organization" class="lokisloop-label">
                            <?php _e('Other', 'lokis-loop'); ?>
                        </label>
                    </div>
                </div>
            </div>

            <div id="lokis-country" class="lokis-formquestion-item">
                <label for="loki-country" class="lokisloop-label">
                    <?php _e('Country', 'lokis-loop'); ?>
                    <span class="lokis-req-field">*</span>
                </label>
                <select id="loki-country" class="lokis-input form-control">
                    <option value=''>
                        <?php // _e('Select a Value', 'lokis-loop'); ?>
                    </option>
                    <option value="United States">
                        <?php _e('United States', 'lokis-loop'); ?>
                    </option>
                    <option value="Afghanistan">
                        <?php _e('Afghanistan', 'lokis-loop'); ?>
                    </option>
                    <option value="Albania">
                        <?php _e('Albania', 'lokis-loop'); ?>
                    </option>
                    <option value="Algeria">
                        <?php _e('Algeria', 'lokis-loop'); ?>
                    </option>
                    <option value="Andorra">
                        <?php _e('Andorra', 'lokis-loop'); ?>
                    </option>
                    <option value="Angola">
                        <?php _e('Angola', 'lokis-loop'); ?>
                    </option>
                    <option value="Antigua & Deps">
                        <?php _e('Antigua & Deps', 'lokis-loop'); ?>
                    </option>
                    <option value="Argentina">
                        <?php _e('Argentina', 'lokis-loop'); ?>
                    </option>
                    <option value="Armenia">
                        <?php _e('Armenia', 'lokis-loop'); ?>
                    </option>
                    <option value="Australia">
                        <?php _e('Australia', 'lokis-loop'); ?>
                    </option>
                    <option value="Austria">
                        <?php _e('Austria', 'lokis-loop'); ?>
                    </option>
                    <option value="Azerbaijan">
                        <?php _e('Azerbaijan', 'lokis-loop'); ?>
                    </option>
                    <option value="Bahamas">
                        <?php _e('Bahamas', 'lokis-loop'); ?>
                    </option>
                    <option value="Bahrain">
                        <?php _e('Bahrain', 'lokis-loop'); ?>
                    </option>
                    <option value="Bangladesh">
                        <?php _e('Bangladesh', 'lokis-loop'); ?>
                    </option>
                    <option value="Barbados">
                        <?php _e('Barbados', 'lokis-loop'); ?>
                    </option>
                    <option value="Belarus">
                        <?php _e('Belarus', 'lokis-loop'); ?>
                    </option>
                    <option value="Belgium">
                        <?php _e('Belgium', 'lokis-loop'); ?>
                    </option>
                    <option value="Belize">
                        <?php _e('Belize', 'lokis-loop'); ?>
                    </option>
                    <option value="Benin">
                        <?php _e('Benin', 'lokis-loop'); ?>
                    </option>
                    <option value="Bhutan">
                        <?php _e('Bhutan', 'lokis-loop'); ?>
                    </option>
                    <option value="Bolivia">
                        <?php _e('Bolivia', 'lokis-loop'); ?>
                    </option>
                    <option value="Bosnia Herzegovina">
                        <?php _e('Bosnia Herzegovina', 'lokis-loop'); ?>
                    </option>
                    <option value="Botswana">
                        <?php _e('Botswana', 'lokis-loop'); ?>
                    </option>
                    <option value="Brazil">
                        <?php _e('Brazil', 'lokis-loop'); ?>
                    </option>
                    <option value="Brunei">
                        <?php _e('Brunei', 'lokis-loop'); ?>
                    </option>
                    <option value="Bulgaria">
                        <?php _e('Bulgaria', 'lokis-loop'); ?>
                    </option>
                    <option value="Burkina">
                        <?php _e('Burkina', 'lokis-loop'); ?>
                    </option>
                    <option value="Burundi">
                        <?php _e('Burundi', 'lokis-loop'); ?>
                    </option>
                    <option value="Cambodia">
                        <?php _e('Cambodia', 'lokis-loop'); ?>
                    </option>
                    <option value="Cameroon">
                        <?php _e('Cameroon', 'lokis-loop'); ?>
                    </option>
                    <option value="Canada">
                        <?php _e('Canada', 'lokis-loop'); ?>
                    </option>
                    <option value="Cape Verde">
                        <?php _e('Cape Verde', 'lokis-loop'); ?>
                    </option>
                    <option value="Central African Rep">
                        <?php _e('Central African Rep', 'lokis-loop'); ?>
                    </option>
                    <option value="Chad">
                        <?php _e('Chad', 'lokis-loop'); ?>
                    </option>
                    <option value="Chile">
                        <?php _e('Chile', 'lokis-loop'); ?>
                    </option>
                    <option value="China">
                        <?php _e('China', 'lokis-loop'); ?>
                    </option>
                    <option value="Colombia">
                        <?php _e('Colombia', 'lokis-loop'); ?>
                    </option>
                    <option value="Comoros">
                        <?php _e('Comoros', 'lokis-loop'); ?>
                    </option>
                    <option value="Congo">
                        <?php _e('Congo', 'lokis-loop'); ?>
                    </option>
                    <option value="Congo {Democratic Rep}">
                        <?php _e('Congo {Democratic Rep}', 'lokis-loop'); ?>
                    </option>
                    <option value="Costa Rica">
                        <?php _e('Costa Rica', 'lokis-loop'); ?>
                    </option>
                    <option value="Croatia">
                        <?php _e('Croatia', 'lokis-loop'); ?>
                    </option>
                    <option value="Cuba">
                        <?php _e('Cuba', 'lokis-loop'); ?>
                    </option>
                    <option value="Cyprus">
                        <?php _e('Cyprus', 'lokis-loop'); ?>
                    </option>
                    <option value="Czech Republic">
                        <?php _e('Czech Republic', 'lokis-loop'); ?>
                    </option>
                    <option value="Denmark">
                        <?php _e('Denmark', 'lokis-loop'); ?>
                    </option>
                    <option value="Djibouti">
                        <?php _e('Djibouti', 'lokis-loop'); ?>
                    </option>
                    <option value="Dominica">
                        <?php _e('Dominica', 'lokis-loop'); ?>
                    </option>
                    <option value="Dominican Republic">
                        <?php _e('Dominican Republic', 'lokis-loop'); ?>
                    </option>
                    <option value="East Timor">
                        <?php _e('East Timor', 'lokis-loop'); ?>
                    </option>
                    <option value="Ecuador">
                        <?php _e('Ecuador', 'lokis-loop'); ?>
                    </option>
                    <option value="Egypt">
                        <?php _e('Egypt', 'lokis-loop'); ?>
                    </option>
                    <option value="El Salvador">
                        <?php _e('El Salvador', 'lokis-loop'); ?>
                    </option>
                    <option value="Equatorial Guinea">
                        <?php _e('Equatorial Guinea', 'lokis-loop'); ?>
                    </option>
                    <option value="Eritrea">
                        <?php _e('Eritrea', 'lokis-loop'); ?>
                    </option>
                    <option value="Estonia">
                        <?php _e('Estonia', 'lokis-loop'); ?>
                    </option>
                    <option value="Ethiopia">
                        <?php _e('Ethiopia', 'lokis-loop'); ?>
                    </option>
                    <option value="Fiji">
                        <?php _e('Fiji', 'lokis-loop'); ?>
                    </option>
                    <option value="Finland">
                        <?php _e('Finland', 'lokis-loop'); ?>
                    </option>
                    <option value="France">
                        <?php _e('France', 'lokis-loop'); ?>
                    </option>
                    <option value="Gabon">
                        <?php _e('Gabon', 'lokis-loop'); ?>
                    </option>
                    <option value="Gambia">
                        <?php _e('Gambia', 'lokis-loop'); ?>
                    </option>
                    <option value="Georgia">
                        <?php _e('Georgia', 'lokis-loop'); ?>
                    </option>
                    <option value="Germany">
                        <?php _e('Germany', 'lokis-loop'); ?>
                    </option>
                    <option value="Ghana">
                        <?php _e('Ghana', 'lokis-loop'); ?>
                    </option>
                    <option value="Greece">
                        <?php _e('Greece', 'lokis-loop'); ?>
                    </option>
                    <option value="Grenada">
                        <?php _e('Grenada', 'lokis-loop'); ?>
                    </option>
                    <option value="Guatemala">
                        <?php _e('Guatemala', 'lokis-loop'); ?>
                    </option>
                    <option value="Guinea">
                        <?php _e('Guinea', 'lokis-loop'); ?>
                    </option>
                    <option value="Guinea-Bissau">
                        <?php _e('Guinea-Bissau', 'lokis-loop'); ?>
                    </option>
                    <option value="Guyana">
                        <?php _e('Guyana', 'lokis-loop'); ?>
                    </option>
                    <option value="Haiti">
                        <?php _e('Haiti', 'lokis-loop'); ?>
                    </option>
                    <option value="Honduras">
                        <?php _e('Honduras', 'lokis-loop'); ?>
                    </option>
                    <option value="Hungary">
                        <?php _e('Hungary', 'lokis-loop'); ?>
                    </option>
                    <option value="Iceland">
                        <?php _e('Iceland', 'lokis-loop'); ?>
                    </option>
                    <option value="India">
                        <?php _e('India', 'lokis-loop'); ?>
                    </option>
                    <option value="Indonesia">
                        <?php _e('Indonesia', 'lokis-loop'); ?>
                    </option>
                    <option value="Iran">
                        <?php _e('Iran', 'lokis-loop'); ?>
                    </option>
                    <option value="Iraq">
                        <?php _e('Iraq', 'lokis-loop'); ?>
                    </option>
                    <option value="Ireland {Republic}">
                        <?php _e('Ireland {Republic}', 'lokis-loop'); ?>
                    </option>
                    <option value="Israel">
                        <?php _e('Israel', 'lokis-loop'); ?>
                    </option>
                    <option value="Italy">
                        <?php _e('Italy', 'lokis-loop'); ?>
                    </option>
                    <option value="Ivory Coast">
                        <?php _e('Ivory Coast', 'lokis-loop'); ?>
                    </option>
                    <option value="Jamaica">
                        <?php _e('Jamaica', 'lokis-loop'); ?>
                    </option>
                    <option value="Japan">
                        <?php _e('Japan', 'lokis-loop'); ?>
                    </option>
                    <option value="Jordan">
                        <?php _e('Jordan', 'lokis-loop'); ?>
                    </option>
                    <option value="Kazakhstan">
                        <?php _e('Kazakhstan', 'lokis-loop'); ?>
                    </option>
                    <option value="Kenya">
                        <?php _e('Kenya', 'lokis-loop'); ?>
                    </option>
                    <option value="Kiribati">
                        <?php _e('Kiribati', 'lokis-loop'); ?>
                    </option>
                    <option value="Korea North">
                        <?php _e('Korea North', 'lokis-loop'); ?>
                    </option>
                    <option value="Korea South">
                        <?php _e('Korea South', 'lokis-loop'); ?>
                    </option>
                    <option value="Kosovo">
                        <?php _e('Kosovo', 'lokis-loop'); ?>
                    </option>
                    <option value="Kuwait">
                        <?php _e('Kuwait', 'lokis-loop'); ?>
                    </option>
                    <option value="Kyrgyzstan">
                        <?php _e('Kyrgyzstan', 'lokis-loop'); ?>
                    </option>
                    <option value="Laos">
                        <?php _e('Laos', 'lokis-loop'); ?>
                    </option>
                    <option value="Latvia">
                        <?php _e('Latvia', 'lokis-loop'); ?>
                    </option>
                    <option value="Lebanon">
                        <?php _e('Lebanon', 'lokis-loop'); ?>
                    </option>
                    <option value="Lesotho">
                        <?php _e('Lesotho', 'lokis-loop'); ?>
                    </option>
                    <option value="Liberia">
                        <?php _e('Liberia', 'lokis-loop'); ?>
                    </option>
                    <option value="Libya">
                        <?php _e('Libya', 'lokis-loop'); ?>
                    </option>
                    <option value="Liechtenstein">
                        <?php _e('Liechtenstein', 'lokis-loop'); ?>
                    </option>
                    <option value="Lithuania">
                        <?php _e('Lithuania', 'lokis-loop'); ?>
                    </option>
                    <option value="Luxembourg">
                        <?php _e('Luxembourg', 'lokis-loop'); ?>
                    </option>
                    <option value="Macedonia">
                        <?php _e('Macedonia', 'lokis-loop'); ?>
                    </option>
                    <option value="Madagascar">
                        <?php _e('Madagascar', 'lokis-loop'); ?>
                    </option>
                    <option value="Malawi">
                        <?php _e('Malawi', 'lokis-loop'); ?>
                    </option>
                    <option value="Malaysia">
                        <?php _e('Malaysia', 'lokis-loop'); ?>
                    </option>
                    <option value="Maldives">
                        <?php _e('Maldives', 'lokis-loop'); ?>
                    </option>
                    <option value="Mali">
                        <?php _e('Mali', 'lokis-loop'); ?>
                    </option>
                    <option value="Malta">
                        <?php _e('Malta', 'lokis-loop'); ?>
                    </option>
                    <option value="Marshall Islands">
                        <?php _e('Marshall Islands', 'lokis-loop'); ?>
                    </option>
                    <option value="Mauritania">
                        <?php _e('Mauritania', 'lokis-loop'); ?>
                    </option>
                    <option value="Mauritius">
                        <?php _e('Mauritius', 'lokis-loop'); ?>
                    </option>
                    <option value="Mexico">
                        <?php _e('Mexico', 'lokis-loop'); ?>
                    </option>
                    <option value="Micronesia">
                        <?php _e('Micronesia', 'lokis-loop'); ?>
                    </option>
                    <option value="Moldova">
                        <?php _e('Moldova', 'lokis-loop'); ?>
                    </option>
                    <option value="Monaco">
                        <?php _e('Monaco', 'lokis-loop'); ?>
                    </option>
                    <option value="Mongolia">
                        <?php _e('Mongolia', 'lokis-loop'); ?>
                    </option>
                    <option value="Montenegro">
                        <?php _e('Montenegro', 'lokis-loop'); ?>
                    </option>
                    <option value="Morocco">
                        <?php _e('Morocco', 'lokis-loop'); ?>
                    </option>
                    <option value="Mozambique">
                        <?php _e('Mozambique', 'lokis-loop'); ?>
                    </option>
                    <option value="Myanmar, {Burma}">
                        <?php _e('Myanmar, {Burma}', 'lokis-loop'); ?>
                    </option>
                    <option value="Namibia">
                        <?php _e('Namibia', 'lokis-loop'); ?>
                    </option>
                    <option value="Nauru">
                        <?php _e('Nauru', 'lokis-loop'); ?>
                    </option>
                    <option value="Nepal">
                        <?php _e('Nepal', 'lokis-loop'); ?>
                    </option>
                    <option value="Netherlands">
                        <?php _e('Netherlands', 'lokis-loop'); ?>
                    </option>
                    <option value="New Zealand">
                        <?php _e('New Zealand', 'lokis-loop'); ?>
                    </option>
                    <option value="Nicaragua">
                        <?php _e('Nicaragua', 'lokis-loop'); ?>
                    </option>
                    <option value="Niger">
                        <?php _e('Niger', 'lokis-loop'); ?>
                    </option>
                    <option value="Nigeria">
                        <?php _e('Nigeria', 'lokis-loop'); ?>
                    </option>
                    <option value="Norway">
                        <?php _e('Norway', 'lokis-loop'); ?>
                    </option>
                    <option value="Oman">
                        <?php _e('Oman', 'lokis-loop'); ?>
                    </option>
                    <option value="Pakistan">
                        <?php _e('Pakistan', 'lokis-loop'); ?>
                    </option>
                    <option value="Palau">
                        <?php _e('Palau', 'lokis-loop'); ?>
                    </option>
                    <option value="Panama">
                        <?php _e('Panama', 'lokis-loop'); ?>
                    </option>
                    <option value="Papua New Guinea">
                        <?php _e('Papua New Guinea', 'lokis-loop'); ?>
                    </option>
                    <option value="Paraguay">
                        <?php _e('Paraguay', 'lokis-loop'); ?>
                    </option>
                    <option value="Peru">
                        <?php _e('Peru', 'lokis-loop'); ?>
                    </option>
                    <option value="Philippines">
                        <?php _e('Philippines', 'lokis-loop'); ?>
                    </option>
                    <option value="Poland">
                        <?php _e('Poland', 'lokis-loop'); ?>
                    </option>
                    <option value="Portugal">
                        <?php _e('Portugal', 'lokis-loop'); ?>
                    </option>
                    <option value="Qatar">
                        <?php _e('Qatar', 'lokis-loop'); ?>
                    </option>
                    <option value="Romania">
                        <?php _e('Romania', 'lokis-loop'); ?>
                    </option>
                    <option value="Russian Federation">
                        <?php _e('Russian Federation', 'lokis-loop'); ?>
                    </option>
                    <option value="Rwanda">
                        <?php _e('Rwanda', 'lokis-loop'); ?>
                    </option>
                    <option value="St Kitts & Nevis">
                        <?php _e('St Kitts & Nevis', 'lokis-loop'); ?>
                    </option>
                    <option value="St Lucia">
                        <?php _e('St Lucia', 'lokis-loop'); ?>
                    </option>
                    <option value="Saint Vincent & the Grenadines">
                        <?php _e('Saint Vincent & the Grenadines', 'lokis-loop'); ?>
                    </option>
                    <option value="Samoa">
                        <?php _e('Samoa', 'lokis-loop'); ?>
                    </option>
                    <option value="San Marino">
                        <?php _e('San Marino', 'lokis-loop'); ?>
                    </option>
                    <option value="Sao Tome & Principe">
                        <?php _e('Sao Tome & Principe', 'lokis-loop'); ?>
                    </option>
                    <option value="Saudi Arabia">
                        <?php _e('Saudi Arabia', 'lokis-loop'); ?>
                    </option>
                    <option value="Senegal">
                        <?php _e('Senegal', 'lokis-loop'); ?>
                    </option>
                    <option value="Serbia">
                        <?php _e('Serbia', 'lokis-loop'); ?>
                    </option>
                    <option value="Seychelles">
                        <?php _e('Seychelles', 'lokis-loop'); ?>
                    </option>
                    <option value="Sierra Leone">
                        <?php _e('Sierra Leone', 'lokis-loop'); ?>
                    </option>
                    <option value="Singapore">
                        <?php _e('Singapore', 'lokis-loop'); ?>
                    </option>
                    <option value="Slovakia">
                        <?php _e('Slovakia', 'lokis-loop'); ?>
                    </option>
                    <option value="Slovenia">
                        <?php _e('Slovenia', 'lokis-loop'); ?>
                    </option>
                    <option value="Solomon Islands">
                        <?php _e('Solomon Islands', 'lokis-loop'); ?>
                    </option>
                    <option value="Somalia">
                        <?php _e('Somalia', 'lokis-loop'); ?>
                    </option>
                    <option value="South Africa">
                        <?php _e('South Africa', 'lokis-loop'); ?>
                    </option>
                    <option value="South Sudan">
                        <?php _e('South Sudan', 'lokis-loop'); ?>
                    </option>
                    <option value="Spain">
                        <?php _e('Spain', 'lokis-loop'); ?>
                    </option>
                    <option value="Sri Lanka">
                        <?php _e('Sri Lanka', 'lokis-loop'); ?>
                    </option>
                    <option value="Sudan">
                        <?php _e('Sudan', 'lokis-loop'); ?>
                    </option>
                    <option value="Suriname">
                        <?php _e('Suriname', 'lokis-loop'); ?>
                    </option>
                    <option value="Swaziland">
                        <?php _e('Swaziland', 'lokis-loop'); ?>
                    </option>
                    <option value="Sweden">
                        <?php _e('Sweden', 'lokis-loop'); ?>
                    </option>
                    <option value="Switzerland">
                        <?php _e('Switzerland', 'lokis-loop'); ?>
                    </option>
                    <option value="Syria">
                        <?php _e('Syria', 'lokis-loop'); ?>
                    </option>
                    <option value="Taiwan">
                        <?php _e('Taiwan', 'lokis-loop'); ?>
                    </option>
                    <option value="Tajikistan">
                        <?php _e('Tajikistan', 'lokis-loop'); ?>
                    </option>
                    <option value="Tanzania">
                        <?php _e('Tanzania', 'lokis-loop'); ?>
                    </option>
                    <option value="Thailand">
                        <?php _e('Thailand', 'lokis-loop'); ?>
                    </option>
                    <option value="Togo">
                        <?php _e('Togo', 'lokis-loop'); ?>
                    </option>
                    <option value="Tonga">
                        <?php _e('Tonga', 'lokis-loop'); ?>
                    </option>
                    <option value="Trinidad & Tobago">
                        <?php _e('Trinidad & Tobago', 'lokis-loop'); ?>
                    </option>
                    <option value="Tunisia">
                        <?php _e('Tunisia', 'lokis-loop'); ?>
                    </option>
                    <option value="Turkey">
                        <?php _e('Turkey', 'lokis-loop'); ?>
                    </option>
                    <option value="Turkmenistan">
                        <?php _e('Turkmenistan', 'lokis-loop'); ?>
                    </option>
                    <option value="Tuvalu">
                        <?php _e('Tuvalu', 'lokis-loop'); ?>
                    </option>
                    <option value="Uganda">
                        <?php _e('Uganda', 'lokis-loop'); ?>
                    </option>
                    <option value="Ukraine">
                        <?php _e('Ukraine', 'lokis-loop'); ?>
                    </option>
                    <option value="United Arab Emirates">
                        <?php _e('United Arab Emirates', 'lokis-loop'); ?>
                    </option>
                    <option value="United Kingdom">
                        <?php _e('United Kingdom', 'lokis-loop'); ?>
                    </option>
                    <option value="Uruguay">
                        <?php _e('Uruguay', 'lokis-loop'); ?>
                    </option>
                    <option value="Uzbekistan">
                        <?php _e('Uzbekistan', 'lokis-loop'); ?>
                    </option>
                    <option value="Vanuatu">
                        <?php _e('Vanuatu', 'lokis-loop'); ?>
                    </option>
                    <option value="Vatican City">
                        <?php _e('Vatican City', 'lokis-loop'); ?>
                    </option>
                    <option value="Venezuela">
                        <?php _e('Venezuela', 'lokis-loop'); ?>
                    </option>
                    <option value="Vietnam">
                        <?php _e('Vietnam', 'lokis-loop'); ?>
                    </option>
                    <option value="Yemen">
                        <?php _e('Yemen', 'lokis-loop'); ?>
                    </option>
                    <option value="Zambia">
                        <?php _e('Zambia', 'lokis-loop'); ?>
                    </option>
                    <option value="Zimbabwe">
                        <?php _e('Zimbabwe', 'lokis-loop'); ?>
                    </option>
                </select>
            </div>

            <div id="lokis-zipcode" class="lokis-formquestion-item">
                <label for="loki-zipcode" class="lokisloop-label">
                    <?php _e('Zipcode(US and Canada only)', 'lokis-loop'); ?>
                </label>
                <input aria-label="single line text" maxlength="4000" class="lokis-input form-control" id='loki-zipcode'>
            </div>

            <div id="lokis-feedback"></div>

            <button class="button" id="lokis-registration-button" value='Submit'>
                <?php _e('Submit', 'lokis-loop'); ?>
            </button>
        </form>
    </div>
    <?php
    return ob_get_clean();
    // }
}
add_shortcode('lokis_loop_register_form', 'lokis_loop_register_form');