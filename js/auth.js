// ===========================
//  EasyAssist · auth.js
//  Login y registro con localStorage (sin backend)
// ===========================

const tabLogin     = document.getElementById("tab-login");
const tabRegister  = document.getElementById("tab-register");
const secLogin     = document.getElementById("login-section");
const secRegister  = document.getElementById("register-section");
const msgBox       = document.getElementById("msg");
const footerLink   = document.getElementById("footer-link");

// ---------- Cambio de pestaña ----------

function switchTab(tab) {
  clearMsg();
  if (tab === "login") {
    tabLogin.classList.add("active");
    tabRegister.classList.remove("active");
    secLogin.classList.add("active");
    secRegister.classList.remove("active");
    footerLink.textContent = "Regístrate";
  } else {
    tabRegister.classList.add("active");
    tabLogin.classList.remove("active");
    secRegister.classList.add("active");
    secLogin.classList.remove("active");
    footerLink.textContent = "Inicia sesión";
  }
}

tabLogin.addEventListener("click", () => switchTab("login"));
tabRegister.addEventListener("click", () => switchTab("register"));

footerLink.addEventListener("click", () => {
  const isLogin = secLogin.classList.contains("active");
  switchTab(isLogin ? "register" : "login");
});

// ---------- Mensajes ----------

function showMsg(text, type) {
  msgBox.textContent = text;
  msgBox.className = "msg " + type;
}

function clearMsg() {
  msgBox.textContent = "";
  msgBox.className = "msg";
}

// ---------- Helpers ----------

function getUsers() {
  return JSON.parse(localStorage.getItem("ea_users") || "[]");
}

function saveUsers(users) {
  localStorage.setItem("ea_users", JSON.stringify(users));
}

function setSession(user) {
  localStorage.setItem("ea_session", JSON.stringify({ name: user.name, email: user.email }));
}

// ---------- LOGIN ----------

document.getElementById("btn-login").addEventListener("click", () => {
  const email    = document.getElementById("login-email").value.trim();
  const password = document.getElementById("login-password").value;

  if (!email || !password) {
    showMsg("Por favor rellena todos los campos.", "error");
    return;
  }

  const users = getUsers();
  const user  = users.find(u => u.email === email && u.password === password);

  if (!user) {
    showMsg("Email o contraseña incorrectos.", "error");
    return;
  }

  setSession(user);
  showMsg("¡Bienvenido de nuevo, " + user.name + "! Redirigiendo...", "success");

  setTimeout(() => {
    window.location.href = "presentacion.html";
  }, 1200);
});

// ---------- REGISTRO ----------

document.getElementById("btn-register").addEventListener("click", () => {
  const name      = document.getElementById("reg-name").value.trim();
  const email     = document.getElementById("reg-email").value.trim();
  const password  = document.getElementById("reg-password").value;
  const password2 = document.getElementById("reg-password2").value;

  if (!name || !email || !password || !password2) {
    showMsg("Por favor rellena todos los campos.", "error");
    return;
  }

  if (password.length < 8) {
    showMsg("La contraseña debe tener al menos 8 caracteres.", "error");
    return;
  }

  if (password !== password2) {
    showMsg("Las contraseñas no coinciden.", "error");
    return;
  }

  const users = getUsers();

  if (users.find(u => u.email === email)) {
    showMsg("Ya existe una cuenta con ese email.", "error");
    return;
  }

  const newUser = { name, email, password };
  users.push(newUser);
  saveUsers(users);
  setSession(newUser);

  showMsg("¡Cuenta creada! Bienvenido, " + name + ". Redirigiendo...", "success");

  setTimeout(() => {
    window.location.href = "presentacion.html";
  }, 1200);
});

// ---------- Enter para enviar ----------

document.addEventListener("keydown", (e) => {
  if (e.key !== "Enter") return;
  if (secLogin.classList.contains("active")) {
    document.getElementById("btn-login").click();
  } else {
    document.getElementById("btn-register").click();
  }
});