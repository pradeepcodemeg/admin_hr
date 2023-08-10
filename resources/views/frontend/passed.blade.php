<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend/global/head')
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div id="testModal" class="">
                <div class="modal-dialog container">
                    <!-- Modal content-->
                    <div class="modal-content row">
                        <div class="modal-body">
                            <p>
                                Congratulations!<br> You’ve successfully passed the test!<br>
                                You can find your certificate of completion in “My Certificates” tab under your account.
                            </p>
                            <form method="post" action="{{ url('submit-test', $training['id']) }}"
                                onsubmit="submitForm(event)">
                                {{ csrf_field() }}
                                <div class="form-inline hidden-xs">
                                    <div class="pull-right bottom">
                                        <button type="submit" id="done_btn"
                                            class="btn btn-primary float-right icon-left  bottom">Done</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('frontend/global/foot')
    <script>
        function submitForm(event) {
            event.preventDefault(); // Prevent the default form submission behavior

            const form = event.target; // Get the form element
            const formData = new FormData(form); // Create a FormData object with form data
            const submitButton = form.querySelector('#done_btn'); // Get the submit button

            submitButton.disabled = true;

            // Make an AJAX call using fetch
            fetch(form.action, {
                    method: "POST",
                    body: formData,
                })
                .then(data => {
                    window.location.href = baseUrl + '/my-files';
                })
                // .catch(error => {
                //     // Handle any errors that occur during the AJAX call
                //     console.error("An error occurred:", error);
                // })
                .finally(() => {
                    submitButton.disabled = false;
                });
        }
    </script>
</body>

</html>
