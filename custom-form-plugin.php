<?php
/*
Plugin Name: Custom Form Plugin
*/

function custom_upload_form_shortcode() {
    ob_start();
    ?>

    <style>
        .inline-block {
            display: inline-block;
            
            margin-right: 20px; /* Add some spacing between the elements */
        }

    </style>

    <form id="uploadForm" action="#" method="post" enctype="multipart/form-data">
        <h3 style="color: #20285D;">Become an Envoy America Companion Driver</h3>

        <div class="wp-block-group inline-block">
            <div class="wp-block-group">
                <span class="inline">First name</span>
                <span class="inline" style="color: red;">*</span>
            </div>
            <span class="inline"><input type="text" style="width: 100%" name="first_name" id="first_name" required></span>
           
        </div>

        <div class="wp-block-group inline-block">
            <div class="wp-block-group">
                <span class="inline">Last name</span>
                <span class="inline" style="color: red;">*</span>
            </div>
            <span class="inline"><input type="text" style="width: 100%" name="last_name" id="last_name" required></span>
           
        </div>
       
       
        <div class="wp-block-group">
            <span class="inline">Email</span>
            <span class="inline" style="color: red;">*</span>
        </div>
        <div class="wp-block-group">
            <input type="email" style="width: 90%" name="email" id="email" required>
        </div>
        <div class="wp-block-group">
            <span class="inline">Zip code</span>
            <span class="inline" style="color: red;">*</span>
        </div>
        <div class="wp-block-group">
            <input type="text" style="width: 40%" name="zip_code" id="zip_code" required>
        </div>
        <div class="wp-block-group">
            <span class="inline">Phone number</span>
            <span class="inline" style="color: red;">*</span>
        </div>
        <div class="wp-block-group">
            <input type="text" style="width: 40%" name="phone" id="phone" required>
            <select name="location" id="location" style="margin: 10px 5px 10px 20px;">
                <option value="palestine">Palestine</option>
                <option value="los_angeles">Los Angeles</option>
            </select>
        </div>

        <div class="wp-block-group">
            <p class="has-small-font-size">Are you willing to complete a background check and drug screen?</p>
            <p class="has-small-font-size">Do you have a 2014 or newer 4-Door Senior friendly vehicle?</p>
        </div>

        <div class="wp-block-group">
            <div class="wp-block-group">
                <input type="checkbox" id="agree1" name="agree1" value="yes" class="inline-block" checked>
                <span class="inline-block">Yes<span style="color: red;">*</span></span>
            </div>
            <div class="wp-block-group">
                <input type="checkbox" id="agree2" name="agree2" value="yes" class="inline-block" checked>
                <span class="inline-block">Yes<span style="color: red;">*</span></span>
            </div>
        </div>

        <p class="has-small-font-size">Envoy America is committed to protecting and respecting your privacy, and we’ll only use your personal information as part of our driver interview process. If you consent to us contacting you for this purpose, please check the box below:</p>
        <input type="checkbox" id="agree3" name="agree3" value="yes" class="inline-block">
        <span class="inline-block">I agree to receive communications from Envoy America.<span style="color: red;">*</span></span>

        <p class="has-small-font-size">You can unsubscribe from these communications at any time. For more information on how to unsubscribe, our privacy practices, and how we are committed to protecting and respecting your privacy, please review our Privacy Policy.</p>
        <p class="has-small-font-size">By clicking submit below, you consent to allow Envoy America to store and process the personal information submitted above to provide you the content requested.</p>

        <label for="photo">Upload Your License</label>
        <input type="file" id="photo" name="photo" accept="image/*" required>
        
        <input type="submit" name="submit" value="Verify">
        <br>
        <label id="validity-label"></label>
    </form>
      <input type="submit" name="submit" value="Submit">

    <script type="text/javascript">
    
    document.getElementById('uploadForm').addEventListener('submit', async function(event) {
        event.preventDefault();
        
        const photoInput = document.getElementById('photo');
        if (!photoInput.files.length) {
            alert('Please choose a document photo to upload.');
            return;
        }

        const apiKey = "aSSlpj5yWSPyi9s2lstqHxWJS6LUhtnH";
        const profileId = "747f05afbb3a4eef8b019f62b4900f84";
        const apiUrl = 'https://api2.idanalyzer.com/scan';

        try {
            const photoBase64 = await encodeFileToBase64(photoInput.files[0]);

            const payload = {
                profile: profileId,
                document: photoBase64
            };

            const response = await fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'X-API-KEY': apiKey,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();
            console.log(data);

            const firstName = document.getElementById('first_name').value;
            const lastName = document.getElementById('last_name').value;
            const firstNameData = data.data.firstName[0].value;
            const lastNameData = data.data.lastName[0].value;
            
            const expirationLicenseDate = data.data.expiry[0].value; 
            const today = new Date().getTime(); 
            const expirationTimestamp = new Date(expirationLicenseDate).getTime();


            const isValid = firstNameData.toLowerCase() === firstName.toLowerCase() &&
                            lastNameData.toLowerCase() === lastName.toLowerCase() &&
                            expirationTimestamp > today

            
            const validityLabel = document.getElementById('validity-label');
            if (isValid) {
                validityLabel.textContent = 'License Validated';
                validityLabel.style.color = 'green';
            } else {
                validityLabel.textContent = 'License Invalid';
                validityLabel.style.color = 'red';
            }

        } catch (error) {
            console.error('Error:', error);
            alert('Upload failed!');
        }
    });

    async function encodeFileToBase64(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onloadend = () => resolve(reader.result.split(',')[1]);
            reader.onerror = reject;
            reader.readAsDataURL(file);
        });
    }
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('hf_form', 'custom_upload_form_shortcode');

?>

