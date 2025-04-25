function mobileMenu() {
    $("#mobileMenu").toggle();
}

function refreshPage() {
    window.location.reload();
}

function openID(id) {
    $(id).toggle();
}

async function register(project_id, project_name, item_id, slot, time) {
    const alert = await Swal.fire({
        title: `ยืนยันการลงทะเบียน<br>${project_name}`,
        html: `วันที่ <span class="text-red-600">${slot}</span><br>รอบ <span class="text-red-600">${time}</span><br>`,
        icon: "question",
        allowOutsideClick: false,
        showConfirmButton: true,
        confirmButtonColor: "green",
        confirmButtonText: "ลงทะเบียน",
        showCancelButton: true,
        cancelButtonColor: "gray",
        cancelButtonText: "ยกเลิก",
    });

    if (alert.isConfirmed) {
        axios
            .post("/hr/project/create", {
                project_id: project_id,
                item_id: item_id,
            })
            .then((res) => {
                Swal.fire({
                    title: res.data.message,
                    icon: "success",
                    confirmButtonText: "ตกลง",
                    confirmButtonColor: "green",
                }).then(() => window.location.reload());
            });
    }
}

async function deleteTransaction(projectId, name) {
    const alert = await Swal.fire({
        title: `ยืนยันการยกเลิกการทะเบียน ${name}`,
        icon: "warning",
        allowOutsideClick: false,
        showConfirmButton: true,
        confirmButtonColor: "red",
        confirmButtonText: "ยืนยัน",
        showCancelButton: true,
        cancelButtonColor: "gray",
        cancelButtonText: "ยกเลิก",
    });

    if (alert.isConfirmed) {
        axios
            .post("/hr/project/delete", {
                project_id: projectId,
            })
            .then((res) => {
                Swal.fire({
                    title: res.data.message,
                    icon: "success",
                    confirmButtonText: "ตกลง",
                    confirmButtonColor: "green",
                }).then(() => window.location.reload());
            });
    }
}

async function sign(id, project_name) {
    alert = await Swal.fire({
        title: "ลงชื่อ : " + project_name,
        icon: "warning",
        allowOutsideClick: false,
        showConfirmButton: true,
        confirmButtonColor: "green",
        confirmButtonText: "ลงชื่อ",
        showCancelButton: true,
        cancelButtonColor: "gray",
        cancelButtonText: "ยกเลิก",
    });

    if (alert.isConfirmed) {
        axios
            .post("/hr/project/sign", {
                transaction_id: id,
            })
            .then((res) => {
                Swal.fire({
                    title: res["data"]["message"],
                    icon: "success",
                    confirmButtonText: "ตกลง",
                    confirmButtonColor: "green",
                }).then(function (isConfirmed) {
                    if (isConfirmed) {
                        window.location.reload();
                    }
                });
            });
    }
}
