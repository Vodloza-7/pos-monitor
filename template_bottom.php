</main>

<script>

function searchTable() {

    let input =
        document.getElementById("search");

    if (!input) return;

    let filter =
        input.value.toLowerCase();

    let rows =
        document.querySelectorAll(
            "#dataTable tbody tr"
        );

    rows.forEach(function(row) {

        let text =
            row.innerText.toLowerCase();

        row.style.display =
            text.includes(filter)
            ? ""
            : "none";

    });

}

</script>

</body>
</html>