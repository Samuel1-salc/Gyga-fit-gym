<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/telaPagamento.css">
    <title>FitClub - Área de Pagamentos</title>
    <script src="https://www.paypal.com/sdk/js?client-id=sb&currency=BRL&locale=pt_BR"></script>
   
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💪 FitClub Academia</h1>
            <p>Escolha seu plano e comece sua jornada fitness hoje mesmo!</p>
        </div>
        
        <div class="main-content">
            <div class="plans-section">
                <h2 class="section-title">Planos Disponíveis</h2>
                
                <div class="plans-grid">
                    <div class="plan-card" data-plan="basico" data-price="89.90">
                        <div class="plan-name">Plano Básico</div>
                        <div class="plan-price">R$ 89,90/mês</div>
                        <ul class="plan-features">
                            <li>Acesso à musculação</li>
                            <li>Horário comercial (6h às 18h)</li>
                            <li>Avaliação física inicial</li>
                            <li>1 aula experimental</li>
                        </ul>
                    </div>
                    
                    <div class="plan-card" data-plan="premium" data-price="129.90">
                        <div class="popular-badge">Mais Popular</div>
                        <div class="plan-name">Plano Premium</div>
                        <div class="plan-price">R$ 129,90/mês</div>
                        <ul class="plan-features">
                            <li>Acesso completo à academia</li>
                            <li>Horário estendido (5h às 23h)</li>
                            <li>Todas as aulas coletivas</li>
                            <li>Avaliação física mensal</li>
                            <li>Treino personalizado</li>
                            <li>Acesso ao app exclusivo</li>
                        </ul>
                    </div>
                    
                    <div class="plan-card" data-plan="vip" data-price="199.90">
                        <div class="plan-name">Plano VIP</div>
                        <div class="plan-price">R$ 199,90/mês</div>
                        <ul class="plan-features">
                            <li>Tudo do Plano Premium</li>
                            <li>Personal trainer 2x/semana</li>
                            <li>Acesso à área VIP</li>
                            <li>Nutricionista incluso</li>
                            <li>Massagem mensal</li>
                            <li>Toalha e shampoo inclusos</li>
                            <li>Estacionamento gratuito</li>
                        </ul>
                    </div>
                </div>
                
                <h2 class="section-title" style="margin-top: 40px;">Pagamentos Avulsos</h2>
                
                <div class="plans-grid">
                    <div class="plan-card" data-plan="day-pass" data-price="25.00">
                        <div class="plan-name">Day Pass</div>
                        <div class="plan-price">R$ 25,00</div>
                        <ul class="plan-features">
                            <li>Acesso por 1 dia</li>
                            <li>Horário comercial</li>
                            <li>Uso de todos os equipamentos</li>
                        </ul>
                    </div>
                    
                    <div class="plan-card" data-plan="personal" data-price="80.00">
                        <div class="plan-name">Aula Personal</div>
                        <div class="plan-price">R$ 80,00</div>
                        <ul class="plan-features">
                            <li>1 hora com personal trainer</li>
                            <li>Treino personalizado</li>
                            <li>Orientação profissional</li>
                        </ul>
                    </div>
                    
                    <div class="plan-card" data-plan="massage" data-price="60.00">
                        <div class="plan-name">Massagem Relaxante</div>
                        <div class="plan-price">R$ 60,00</div>
                        <ul class="plan-features">
                            <li>50 minutos de massagem</li>
                            <li>Profissional especializado</li>
                            <li>Ambiente climatizado</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="payment-section">
                <h2 class="section-title">Finalizar Pagamento</h2>
                
                <div class="user-info">
                    <h3>Seus Dados</h3>
                    <form id="user-data-form" class="user-details" autocomplete="on" style="display:flex;flex-direction:column;gap:10px;max-width:400px;">
                        <label><strong>Nome:</strong>
                            <input type="text" id="user-name" name="user-name" placeholder="Digite seu nome completo" required>
                        </label>
                        <label><strong>Email:</strong>
                            <input type="email" id="user-email" name="user-email" placeholder="Digite seu email" required>
                        </label>
                        <label><strong>CPF:</strong>
                            <input type="text" id="user-cpf" name="user-cpf" placeholder="000.000.000-00" maxlength="14" required pattern="\d{3}\.\d{3}\.\d{3}-\d{2}">
                        </label>
                        <label><strong>Telefone:</strong>
                            <input type="tel" id="user-phone" name="user-phone" placeholder="(00) 00000-0000" maxlength="15" required pattern="\(\d{2}\) \d{5}-\d{4}">
                        </label>
                    </form>
                </div>
                
                <div class="payment-summary">
                    <div class="summary-item">
                        <span>Plano selecionado:</span>
                        <span id="selected-plan">Nenhum plano selecionado</span>
                    </div>
                    <div class="summary-item">
                        <span>Valor:</span>
                        <span id="plan-value">R$ 0,00</span>
                    </div>
                    <div class="summary-item">
                        <span>Desconto:</span>
                        <span id="discount-value">- R$ 0,00</span>
                    </div>
                    <div class="summary-item">
                        <span>Total a pagar:</span>
                        <span id="total-value">R$ 0,00</span>
                    </div>
                </div>
                
                <div class="discount-section">
                    <h4>Cupom de Desconto</h4>
                    <div class="discount-input">
                        <input type="text" id="discount-code" placeholder="Digite seu cupom">
                        <button onclick="applyDiscount()">Aplicar</button>
                    </div>
                </div>
                
                <div id="payment-buttons" style="display: none;">
                    <div class="paypal-container">
                        <div id="paypal-button-container"></div>
                    </div>
                    
                    <div class="payment-divider">
                        <span>ou</span>
                    </div>
                    
                    <div class="alternative-payments">
                        <button class="credit-card-btn" onclick="showCreditCardForm()">
                            💳 Pagar com Cartão de Crédito
                        </button>
                    </div>
                </div>
                
                <div id="select-plan-message" class="loading">
                    Selecione um plano para continuar
                </div>
                
                <div id="success-message" class="success-message">
                    <h3>🎉 Pagamento realizado com sucesso!</h3>
                    <p>Bem-vindo à FitClub Academia!</p>
                    <p>Você receberá um email com todas as informações.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedPlan = null;
        let planPrice = 0;
        let discountAmount = 0;
        
        // Cupons de desconto disponíveis
        const discountCoupons = {
            'PRIMEIRA': 20, // 20% de desconto
            'ESTUDANTE': 15, // 15% de desconto
            'FAMILIA': 10,   // 10% de desconto
            'VOLTA20': 25    // 25% de desconto
        };
        
        // Seleção de planos
        document.querySelectorAll('.plan-card').forEach(card => {
            card.addEventListener('click', function() {
                // Remove seleção anterior
                document.querySelectorAll('.plan-card').forEach(c => c.classList.remove('selected'));
                
                // Adiciona seleção atual
                this.classList.add('selected');
                
                // Atualiza informações do plano
                selectedPlan = this.dataset.plan;
                planPrice = parseFloat(this.dataset.price);
                
                updatePaymentSummary();
                showPaymentButtons();
            });
        });
        
        function updatePaymentSummary() {
            const planNames = {
                'basico': 'Plano Básico',
                'premium': 'Plano Premium',
                'vip': 'Plano VIP',
                'day-pass': 'Day Pass',
                'personal': 'Aula Personal',
                'massage': 'Massagem Relaxante'
            };
            
            document.getElementById('selected-plan').textContent = planNames[selectedPlan] || 'Nenhum plano selecionado';
            document.getElementById('plan-value').textContent = `R$ ${planPrice.toFixed(2).replace('.', ',')}`;
            
            const discount = (planPrice * discountAmount / 100);
            document.getElementById('discount-value').textContent = `- R$ ${discount.toFixed(2).replace('.', ',')}`;
            
            const total = planPrice - discount;
            document.getElementById('total-value').textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;
        }
        
        function showPaymentButtons() {
            document.getElementById('select-plan-message').style.display = 'none';
            document.getElementById('payment-buttons').style.display = 'block';
            
            // Reinicializa o botão do PayPal
            initPayPalButton();
        }
        
        function applyDiscount() {
            const code = document.getElementById('discount-code').value.toUpperCase();
            
            if (discountCoupons[code]) {
                discountAmount = discountCoupons[code];
                updatePaymentSummary();
                
                // Visual feedback
                const input = document.getElementById('discount-code');
                input.style.borderColor = '#48bb78';
                input.style.background = '#f0fff4';
                
                setTimeout(() => {
                    input.style.borderColor = '#cbd5e0';
                    input.style.background = 'white';
                }, 2000);
                
                alert(`Cupom aplicado! ${discountAmount}% de desconto`);
            } else {
                discountAmount = 0;
                updatePaymentSummary();
                alert('Cupom inválido. Tente: PRIMEIRA, ESTUDANTE, FAMILIA ou VOLTA20');
            }
        }
        
        function initPayPalButton() {
            // Limpa o container anterior
            document.getElementById('paypal-button-container').innerHTML = '';
            
            if (!selectedPlan) return;
            
            const total = planPrice - (planPrice * discountAmount / 100);
            
            paypal.Buttons({
                style: {
                    color: 'blue',
                    shape: 'rect',
                    label: 'paypal',
                    layout: 'vertical'
                },
                
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: total.toFixed(2),
                                currency_code: 'BRL'
                            },
                            description: `FitClub Academia - ${document.getElementById('selected-plan').textContent}`
                        }]
                    });
                },
                
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        console.log('Pagamento aprovado:', details);
                        showSuccessMessage();
                    });
                },
                
                onError: function(err) {
                    console.error('Erro no pagamento:', err);
                    alert('Erro ao processar pagamento. Tente novamente.');
                },
                
                onCancel: function(data) {
                    console.log('Pagamento cancelado:', data);
                    alert('Pagamento cancelado pelo usuário.');
                }
                
            }).render('#paypal-button-container');
        }
        
        function showCreditCardForm() {
            alert('Integração com cartão de crédito seria implementada aqui.\n\nEm um ambiente real, você integraria com:\n- PayPal Advanced Credit and Debit Card Payments\n- Stripe\n- PagSeguro\n- Cielo\n- Mercado Pago');
        }
        
        function showSuccessMessage() {
            document.getElementById('payment-buttons').style.display = 'none';
            document.getElementById('success-message').style.display = 'block';
            
            // Simula redirecionamento após alguns segundos
            setTimeout(() => {
                alert('Em um sistema real, você seria redirecionado para a área do cliente.');
            }, 3000);
        }
        
        // Validação simples para garantir que os dados do usuário foram preenchidos antes do pagamento
        function validateUserData() {
            const name = document.getElementById('user-name').value.trim();
            const email = document.getElementById('user-email').value.trim();
            const cpf = document.getElementById('user-cpf').value.trim();
            const phone = document.getElementById('user-phone').value.trim();
            if (!name || !email || !cpf || !phone) {
                alert('Por favor, preencha todos os dados antes de prosseguir com o pagamento.');
                return false;
            }
            return true;
        }
        // Intercepta o clique nos botões de pagamento
        function blockPaymentIfNoUserData() {
            const paymentSection = document.getElementById('payment-buttons');
            if (!paymentSection) return;
            paymentSection.addEventListener('click', function(e) {
                if (!validateUserData()) {
                    e.stopPropagation();
                    e.preventDefault();
                }
            }, true);
        }
        document.addEventListener('DOMContentLoaded', blockPaymentIfNoUserData);
        
        // Inicialização
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Sistema de pagamentos FitClub Academia iniciado');
            console.log('Ambiente Sandbox do PayPal configurado');
            console.log('Cupons disponíveis: PRIMEIRA (20%), ESTUDANTE (15%), FAMILIA (10%), VOLTA20 (25%)');
        });
    </script>
</body>
</html>