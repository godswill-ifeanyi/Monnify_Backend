<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Clients Account — Chamber account for lawyers</title>
  <meta name="description" content="Clients Account — Chamber accounts, invoicing, payments & ledger for law chambers." />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
  <header class="site-header">
    <div class="container header-inner">
      <a href="{{ url('/') }}" class="brand">
        <svg width="36" height="36" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
          <rect x="2" y="3" width="20" height="18" rx="2" fill="#0b71eb"></rect>
          <path d="M6 7h12v2H6z" fill="#fff" opacity="0.95"></path>
          <path d="M6 11h8v6H6z" fill="#fff" opacity="0.12"></path>
        </svg>
        <span>Clients Account</span>
      </a>

      <nav class="main-nav" aria-label="Main navigation">
        <a href="#features">Features</a>
        <a href="#how-it-works">How it works</a>
        <a href="#pricing">Pricing</a>
        <a href="#contact">Contact</a>
      </nav>

      <div class="header-ctas">
        <a class="btn btn-outline" href="#pricing">Get Started</a>
        <button id="mobileMenuBtn" class="mobile-menu-btn" aria-label="Open menu">☰</button>
      </div>
    </div>

    <!-- mobile menu -->
    <div id="mobileMenu" class="mobile-menu" aria-hidden="true">
      <a href="#features" class="mobile-link">Features</a>
      <a href="#how-it-works" class="mobile-link">How it works</a>
      <a href="#pricing" class="mobile-link">Pricing</a>
      <a href="#contact" class="mobile-link">Contact</a>
      <a href="#pricing" class="btn btn-primary mobile-cta">Get Started</a>
    </div>
  </header>

  <main>
    <section class="hero">
      <div class="container hero-grid">
        <div class="hero-left">
          <h1>Clients Account — chamber account made for lawyers</h1>
          <p class="lead">
            Manage trust accounts, invoices, and payments in Nigerian Naira — with automated monthly fees, arrears handling, and real-time notifications.
          </p>

          <div class="hero-actions">
            <a href="#pricing" class="btn btn-primary">Start Free Trial</a>
            <a href="#how-it-works" class="btn btn-link">How it works</a>
          </div>

          <ul class="trust-list">
            <li><strong>Monnify integration</strong> — virtual accounts & webhooks</li>
            <li><strong>Arrears-aware billing</strong> — auto-deduct & partially settle</li>
            <li><strong>Queued processing</strong> — reliable background jobs</li>
          </ul>
        </div>

        <div class="hero-right" aria-hidden="true">
          <div class="mock-card">
            <div class="balance-row">
              <div>
                <div class="label">Trust Balance</div>
                <div class="amount">₦<span id="demoBalance">12,450.00</span></div>
              </div>
              <div class="mini">Next fee: ₦750</div>
            </div>

            <div class="txn-list">
              <div class="txn credit">
                <div class="txn-left">
                  <div class="txn-title">Deposit</div>
                  <div class="txn-sub">From Monnify Limited</div>
                </div>
                <div class="txn-right">+₦3,000</div>
              </div>

              <div class="txn debit">
                <div class="txn-left">
                  <div class="txn-title">Monthly fee</div>
                  <div class="txn-sub">Auto-deduct</div>
                </div>
                <div class="txn-right">-₦750</div>
              </div>
            </div>

            <div class="mock-footer">
              <small>Live updates via Pusher</small>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="features" class="container features">
      <h2>Features built for chambers</h2>
      <div class="features-grid">
        <article>
          <h3>Trust & virtual accounts</h3>
          <p>Create reserved accounts per client, accept NGN deposits and reconcile automatically.</p>
        </article>

        <article>
          <h3>Invoicing & payments</h3>
          <p>Generate invoices, accept card & bank transfers, and record deposits & disbursements.</p>
        </article>

        <article>
          <h3>Arrears & automatic deductions</h3>
          <p>Monthly fee is added to arrears and partially/fully deducted when funds arrive.</p>
        </article>

        <article>
          <h3>Secure webhooks</h3>
          <p>IP + signature verification for Monnify notifications and idempotent processing.</p>
        </article>

        <article>
          <h3>Queued background jobs</h3>
          <p>All heavy work is queued — reliable workers via Supervisor or cron + queue:work.</p>
        </article>

        <article>
          <h3>Real-time notifications</h3>
          <p>Instant frontend updates using Pusher/Laravel Echo for deposits and deductions.</p>
        </article>
      </div>
    </section>

    <section id="how-it-works" class="how container">
      <h2>How it works</h2>
      <div class="steps">
        <div class="step">
          <div class="step-num">1</div>
          <div class="step-body">
            <h4>Create virtual accounts</h4>
            <p>Provision Monnify reserved accounts for clients and link to their records.</p>
          </div>
        </div>

        <div class="step">
          <div class="step-num">2</div>
          <div class="step-body">
            <h4>Receive webhooks</h4>
            <p>Monnify posts immediately to your webhook. We verify signature & queue the job.</p>
          </div>
        </div>

        <div class="step">
          <div class="step-num">3</div>
          <div class="step-body">
            <h4>Process deposits</h4>
            <p>Queued job writes credit transaction, stores deposit detail, deducts arrears and updates balance.</p>
          </div>
        </div>

        <div class="step">
          <div class="step-num">4</div>
          <div class="step-body">
            <h4>Real-time updates</h4>
            <p>Frontend receives an event and updates the UI instantly. Easy audit trail and receipts.</p>
          </div>
        </div>
      </div>
    </section>

    <section id="pricing" class="pricing container">
      <h2>Pricing</h2>
      <div class="pricing-grid">
        <div class="card price-card">
          <h3>Starter</h3>
          <p class="price">₦0 <small>/ month</small></p>
          <ul>
            <li>Up to 10 virtual accounts</li>
            <li>Basic invoicing</li>
            <li>Email support</li>
          </ul>
          <a href="#" class="btn btn-outline">Get Started</a>
        </div>

        <div class="card price-card featured">
          <h3>Pro</h3>
          <p class="price">₦5,000 <small>/ month</small></p>
          <ul>
            <li>Unlimited virtual accounts</li>
            <li>Auto deductions & arrears handling</li>
            <li>Priority support & Pusher notifications</li>
          </ul>
          <a href="#" class="btn btn-primary">Try Pro Free</a>
        </div>

        <div class="card price-card">
          <h3>Enterprise</h3>
          <p class="price">Custom</p>
          <ul>
            <li>On-premise or dedicated infra</li>
            <li>SLA & custom integrations</li>
            <li>Phone support</li>
          </ul>
          <a href="#" class="btn btn-outline">Contact Sales</a>
        </div>
      </div>
    </section>

    <section id="contact" class="contact container">
      <h2>Contact & demo</h2>
      <p>Want a demo or help integrating Monnify and Pusher? Drop a message — we’ll get back within one business day.</p>

      <form id="contactForm" class="contact-form" action="#" method="post" novalidate>
        <div class="form-row">
          <input type="text" id="name" name="name" placeholder="Your name" required>
          <input type="email" id="email" name="email" placeholder="Email address" required>
        </div>
        <div class="form-row">
          <input type="text" id="firm" name="firm" placeholder="Chamber / Firm name">
        </div>
        <div class="form-row">
          <textarea id="message" name="message" rows="4" placeholder="How can we help?" required></textarea>
        </div>

        <div class="form-row form-actions">
          <button type="submit" class="btn btn-primary">Send message</button>
          <div id="formStatus" class="form-status" aria-live="polite"></div>
        </div>
      </form>
    </section>
  </main>

  <footer class="site-footer">
    <div class="container footer-inner">
      <div>© <span id="year"></span> Clients Account</div>
      <nav class="footer-nav">
        <a href="#">Privacy</a>
        <a href="#">Terms</a>
        <a href="#">Support</a>
      </nav>
    </div>
  </footer>

  <div id="toast" class="toast" role="status" aria-live="polite"></div>

  <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
