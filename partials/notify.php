<?php 
function displayToast($message, $type = 'success') {
    echo '<script>Toastify({
        text: "'.$message.'",
        duration: 3000,
        close: true,
        gravity: "top",
        position: "right",
        backgroundColor: "'.($type == 'success' ? '#4CAF50' : '#f44336').'",
        stopOnFocus: true,
        style: {
            color: "white"
        },
        progressBar: true,
        autoClose: true
    }).showToast();</script>';
}

?>