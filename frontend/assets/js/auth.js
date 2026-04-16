// file này xử lý về guard, login, register, profile, logout

// Kiểm tra user đã đăng nhập chưa, nếu chưa thì chuyển về login 
function checkLogin() {
    let token = localStorage.getItem("token")
    if (token == null || token == "") {
        alert("Ban can dang nhap truoc!")
        window.location.href = "/frontend/pages/auth/login.php"
    }
}


// Xử lý nút đăng nhập
let btnLogin = document.getElementById("btnLogin")
if (btnLogin) {
    btnLogin.addEventListener("click", async function() {

        let email = document.getElementById("email").value
        let pass  = document.getElementById("password").value
        let thongbao = document.getElementById("errorMsg")

        // Nếu nhập thiếu thông tin thì hiện thông báo lỗi
         if (email == "" || pass == "") { 
            thongbao.style.display = "block"
            thongbao.innerText = "Vui lòng nhập đầy đủ thông tin!"
            return
        }

        btnLogin.innerText = "Dang xu ly..."
        btnLogin.disabled = true
        thongbao.style.display = "none"

        try {
            let res = await apiPost("/auth/login", {
                email: email,
                password: pass
            })      

            if (res.success == true) { // login đúng
                // Lưu token và role vào localstorage
                localStorage.setItem("token", res.data.token)
                localStorage.setItem("role", res.data.role)

                // Chuyển trang theo role 
                if (res.data.role == "admin" || res.data.role == "staff") {
                    window.location.href = "/frontend/admin/dashboard.php"
                } else {
                    window.location.href = "/frontend/pages/shop/index.php"
                }

            } else { // login sai
                thongbao.style.display = "block"
                thongbao.innerText = res.message
                btnLogin.innerText = "Dang nhap"
                btnLogin.disabled = false
            }

        } catch(err) { // Lỗi kết nối server
            thongbao.style.display = "block"
            thongbao.innerText = "Loi ket noi server!"
            btnLogin.innerText = "Dang nhap"
            btnLogin.disabled = false
        }
    })
}


// Xử lý nút đăng ký
let btnRegister = document.getElementById("btnRegister")
if (btnRegister) {
    btnRegister.addEventListener("click", async function() {

        let ten     = document.getElementById("fullName").value
        let email   = document.getElementById("email").value
        let pass    = document.getElementById("password").value
        let sdt     = document.getElementById("phone").value
        let diachi  = document.getElementById("address").value

        let loi     = document.getElementById("errorMsg")
        let thanhcong = document.getElementById("successMsg")

        loi.style.display = "none"
        thanhcong.style.display = "none"

        if (ten == "" || email == "" || pass == "" || sdt == "" || diachi == "") {
            loi.style.display = "block"
            loi.innerText = "Vui lòng nhập đầy đủ thông tin!"
            return
        }

        if (pass.length < 6) {
            loi.style.display = "block"
            loi.innerText = "Mật khẩu phải có ít nhất 6 ký tự!"
            return
        }

        btnRegister.innerText = "Dang xu ly..."
        btnRegister.disabled = true

        try {
            let res = await apiPost("/auth/register", {
                full_name: ten,
                email:     email,
                password:  pass,
                phone:     sdt,
                address:   diachi
            })

            if (res.success == true) {
                thanhcong.style.display = "block"
                thanhcong.innerText = "Dang ky thanh cong! Dang chuyen trang..."
                setTimeout(function() {
                    window.location.href = "/frontend/pages/auth/login.php"
                }, 1500)
            } else {
                loi.style.display = "block"
                loi.innerText = res.message
                btnRegister.innerText = "Get Started"
                btnRegister.disabled = false
            }

        } catch(err) {
            loi.style.display = "block"
            loi.innerText = "Loi ket noi server!"
            btnRegister.innerText = "Get Started"
            btnRegister.disabled = false
        }
    })
}


// đăng xuất
async function dangXuat() {
    try {
        await apiPost("/auth/logout", {})
    } catch(err) {
        console.log("loi logout")
    }

    localStorage.removeItem("token")
    localStorage.removeItem("role")
    window.location.href = "/frontend/pages/auth/login.php"
}


// profile - hiển thị thông tin user và có thể chỉnh sửa thông tin, xem lịch sử đơn hàng
// lưu thông tin cũ
let thongTinCu = {}

if (document.getElementById("profileName")) {
    window.onload = function() {
        loadThongTin()
        loadDonHang()
    }
}

async function loadThongTin() {
    try {
        let res = await apiGet("/auth/me")
        if (res.success == true) {
            let u = res.data
            thongTinCu = u
// hiển thị thông tin lên profile
            document.getElementById("profileName").innerText  = u.full_name
            document.getElementById("profileEmail").innerText = u.email
            document.getElementById("inputName").value    = u.full_name
            document.getElementById("inputEmail").value   = u.email
            document.getElementById("inputPhone").value   = u.phone
            document.getElementById("inputAddress").value = u.address

            if (u.avatar) {
                document.getElementById("avatarImg").src = u.avatar
            } else {
                document.getElementById("avatarImg").src = "https://ui-avatars.com/api/?name=" + u.full_name + "&background=c76eb0&color=fff&size=128"
            }
        }
    } catch(err) {
        console.log("load thông tin bị lỗi")
    }
}

// sửa thông tin
function batDauSua() {
    document.getElementById("inputName").disabled    = false
    document.getElementById("inputPhone").disabled   = false
    document.getElementById("inputAddress").disabled = false
    document.getElementById("btnEdit").style.display   = "none"
    document.getElementById("btnLuuBox").style.display = "block"
}

// hủy sửa thông tin và trả về thông tin cũ
function huy() {
    document.getElementById("inputName").value    = thongTinCu.full_name
    document.getElementById("inputPhone").value   = thongTinCu.phone
    document.getElementById("inputAddress").value = thongTinCu.address
    document.getElementById("inputName").disabled    = true
    document.getElementById("inputPhone").disabled   = true
    document.getElementById("inputAddress").disabled = true
    document.getElementById("btnEdit").style.display   = "inline-block"
    document.getElementById("btnLuuBox").style.display = "none"
}

// lưu thông tin đã sửa
async function luu() {
    let ten    = document.getElementById("inputName").value
    let sdt    = document.getElementById("inputPhone").value
    let diachi = document.getElementById("inputAddress").value

    if (ten == "" || sdt == "" || diachi == "") {
        alert("Vui lòng nhập đầy đủ thông tin!")
        return
    }

    let btnSave = document.getElementById("btnSave")
    btnSave.disabled = true
    btnSave.innerText = "Dang luu..."

    try {
        let res = await apiPut("/auth/me", {
            full_name: ten,
            phone:     sdt,
            address:   diachi
        })

        if (res.success == true) {
            thongTinCu.full_name = ten
            thongTinCu.phone     = sdt
            thongTinCu.address   = diachi
            document.getElementById("profileName").innerText = ten
            alert("Cập nhật thông tin thành công!")
            huy()
        } else {
            alert(res.message)
        }

    } catch(err) {
        alert("Loi ket noi server!")
    }

    btnSave.disabled  = false
    btnSave.innerText = "Luu thay doi"
}

// load đơn hàng của người dùng
async function loadDonHang() {
    let khu = document.getElementById("orderList")

    try {
        let res = await apiGet("/orders")

        if (res.success == true) {
            let danhsach = res.data.items

            if (danhsach.length == 0) {
                khu.innerHTML = "<p>Bạn chưa có đơn hàng nào</p>"
                return
            }

            let html = ""
            for (let i = 0; i < danhsach.length; i++) {
                let don = danhsach[i]
                html += `
                    <div class="order-item">
                        <div>
                            <b>${don.order_code}</b>
                            <span>${don.created_at}</span>
                        </div>
                        <div>
                            <span>${don.total_amount.toLocaleString()}d</span>
                            <span class="status-${don.order_status}">${dich(don.order_status)}</span>
                        </div>
                    </div>
                `
            }
            khu.innerHTML = html

        } else {
            khu.innerHTML = "<p>Không tải được đơn hàng</p>"
        }

    } catch(err) {
        khu.innerHTML = "<p>Lỗi kết nối</p>"
    }
}

// chuyển trạng thái đơn hàng
function dich(s) {
    if (s == "pending")   return "Chờ xác nhận"
    if (s == "confirmed") return "Đã xác nhận"
    if (s == "shipping")  return "Đang giao"
    if (s == "delivered") return "Đã giao"
    if (s == "cancelled") return "Đã hủy"
    return s
}