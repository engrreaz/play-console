<?php include('inc.php'); ?>

<div class="container py-5" style="max-width:700px;">

    <div class="card shadow-sm border-0">
        <div class="card-body text-center">

            <h3 class="text-danger mb-3">Danger Zone</h3>

            <p class="text-muted">
                Once you delete your account, all your data will be permanently removed
                after approval/processing. This action cannot be undone.
            </p>

            <button id="deleteBtn" class="btn btn-danger btn-lg">
                Delete My Account
            </button>

        </div>
    </div>

</div>

<script>
document.getElementById("deleteBtn").addEventListener("click", function () {

    Swal.fire({
        title: "Are you sure?",
        text: "This will permanently delete your account request.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {

        if (result.isConfirmed) {

            fetch("ajax/confirm-delete-account.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({})
            })
            .then(res => res.json())
            .then(data => {

                if (data.status === "success") {
                    Swal.fire("Requested!", data.message, "success");
                } else {
                    Swal.fire("Error!", data.message, "error");
                }

            })
            .catch(() => {
                Swal.fire("Error!", "Something went wrong.", "error");
            });

        }

    });

});
</script>

<?php include('footer.php'); ?>