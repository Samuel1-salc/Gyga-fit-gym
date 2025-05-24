// Funções JavaScript para interatividade

// Toggle do menu
function toggleMenu() {
    console.log("Menu toggled")
    // Implementar lógica do menu lateral
}

// Toggle das notificações
function toggleNotifications() {
    console.log("Notifications toggled")
    // Implementar lógica das notificações
}

// Abrir configurações
function abrirConfiguracoes() {
    console.log("Configurações abertas")
    // Implementar lógica das configurações
}

// Editar perfil
function editarPerfil() {
    console.log("Perfil editado")
    // Implementar lógica de edição de perfil
}

// Links do footer
function faleConosco() {
    console.log("Fale conosco")
    // Implementar lógica de contato
}

function politicaPrivacidade() {
    console.log("Política de privacidade")
    // Implementar lógica da política
}

// Redes sociais
function abrirFacebook() {
    window.open("https://facebook.com/gygafit", "_blank")
}

function abrirInstagram() {
    window.open("https://instagram.com/gygafit", "_blank")
}

function abrirYoutube() {
    window.open("https://youtube.com/gygafit", "_blank")
}

// Validação do formulário
document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector(".form-moderno")
    const inputs = form.querySelectorAll("input[required]")

    // Adicionar validação em tempo real
    inputs.forEach((input) => {
        input.addEventListener("blur", function () {
            validateField(this)
        })

        input.addEventListener("input", function () {
            if (this.classList.contains("error")) {
                validateField(this)
            }
        })
    })

    // Validação no envio
    form.addEventListener("submit", (e) => {
        let isValid = true

        inputs.forEach((input) => {
            if (!validateField(input)) {
                isValid = false
            }
        })

        if (!isValid) {
            e.preventDefault()
            showNotification("Por favor, preencha todos os campos obrigatórios.", "error")
        } else {
            showNotification("Formulário enviado com sucesso!", "success")
        }
    })
})

// Função de validação de campo
function validateField(field) {
    const value = field.value.trim()
    const isValid = value !== ""

    if (isValid) {
        field.classList.remove("error")
        field.classList.add("valid")
    } else {
        field.classList.remove("valid")
        field.classList.add("error")
    }

    return isValid
}

// Função para mostrar notificações
function showNotification(message, type = "info") {
    // Criar elemento de notificação
    const notification = document.createElement("div")
    notification.className = `notification notification-${type}`
    notification.textContent = message

    // Adicionar estilos
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 16px 24px;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        z-index: 10000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        ${type === "success" ? "background: #10b981;" : ""}
        ${type === "error" ? "background: #ef4444;" : ""}
        ${type === "info" ? "background: #3b82f6;" : ""}
    `

    // Adicionar ao DOM
    document.body.appendChild(notification)

    // Animar entrada
    setTimeout(() => {
        notification.style.transform = "translateX(0)"
    }, 100)

    // Remover após 3 segundos
    setTimeout(() => {
        notification.style.transform = "translateX(100%)"
        setTimeout(() => {
            document.body.removeChild(notification)
        }, 300)
    }, 3000)
}

// Adicionar efeitos de hover nos cards
document.addEventListener("DOMContentLoaded", () => {
    const cards = document.querySelectorAll(".option-card, .frequency-card, .gender-card")

    cards.forEach((card) => {
        card.addEventListener("mouseenter", function () {
            this.style.transform = "translateY(-2px)"
        })

        card.addEventListener("mouseleave", function () {
            if (!this.querySelector('input[type="radio"]:checked')) {
                this.style.transform = "translateY(0)"
            }
        })
    })
})

// Smooth scroll para elementos
function smoothScrollTo(element) {
    element.scrollIntoView({
        behavior: "smooth",
        block: "center",
    })
}

// Auto-save do formulário (opcional)
let autoSaveTimer
function autoSaveForm() {
    clearTimeout(autoSaveTimer)
    autoSaveTimer = setTimeout(() => {
        const formData = new FormData(document.querySelector(".form-moderno"))
        const data = Object.fromEntries(formData)
        localStorage.setItem("gyga_form_draft", JSON.stringify(data))
        console.log("Formulário salvo automaticamente")
    }, 2000)
}

// Carregar dados salvos
document.addEventListener("DOMContentLoaded", () => {
    const savedData = localStorage.getItem("gyga_form_draft")
    if (savedData) {
        const data = JSON.parse(savedData)
        Object.keys(data).forEach((key) => {
            const field = document.querySelector(`[name="${key}"]`)
            if (field) {
                if (field.type === "radio") {
                    const radio = document.querySelector(`[name="${key}"][value="${data[key]}"]`)
                    if (radio) radio.checked = true
                } else {
                    field.value = data[key]
                }
            }
        })
    }

    // Adicionar listeners para auto-save
    const form = document.querySelector(".form-moderno")
    form.addEventListener("input", autoSaveForm)
    form.addEventListener("change", autoSaveForm)
})
