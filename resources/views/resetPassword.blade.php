<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <link rel="stylesheet" href="{{asset('./styles.css')}}" />

  <meta name="description" content="password reset for golden woods" />
</head>

<body>
  <main class="reset-page">
    <section class="reset-container">
      <header class="reset__header">
        <img class="reset-icon" src="{{asset('./images/lock.svg')}}" alt="reset logo" />
        <h2 class="reset__heading">Please reset your password</h2>
      </header>
      <div class="reset__content">
        <p>Hello,</p>
        <p>
          We have sent you this email in response to your request to reset
          your password for golden woods admin panel
        </p>
        <p>To reset your password, please copy the token below:</p>
        <p>{{ $token }}</p>
      </div>
      <footer class="footer-container">
        <div class="footer__content">
          <p>Golden Woods Developers</p>
          <p>3rd Floor Subham Buildwell, Block-H, Zoo Road</p>
          <p>Guwahati, Assam, 781006 | +91 84484 44840</p>
        </div>
        <ul class="footer__list">
          <li>
            <a href="https://www.google.com" class="footer__link">
              <img src="{{asset('./images/facebook.png')}}" alt="facebook link" />
            </a>
          </li>
          <li>
            <a href="https://www.google.com" class="footer__link">
              <img src="{{asset('./images/twitter.png')}}" alt="twitter link" />
            </a>
          </li>
          <li>
            <a href="https://www.google.com" class="footer__link">
              <img src="{{asset('./images/instagram.png')}}" alt="instagram link" />
            </a>
          </li>
        </ul>
        <p>Golden Woods 2023 &copy; All Rights Reserved</p>
      </footer>
    </section>
  </main>
</body>

</html>