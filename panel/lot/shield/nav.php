<?php echo panel\nav(Config::get('panel.nav', []), 'main'); ?>

<!--
  <ul>
    <li class="lot">
      <a href=""><svg class="icon only" viewBox="0 0 24 24"><path d="M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z"></path></svg></a>
      <ul class="main">
        <li><a href=""><svg class="icon left" viewBox="0 0 24 24"><path d=""></path></svg> <span>Cache</span></a></li>
        <li><a href=""><svg class="icon left" viewBox="0 0 24 24"><path d=""></path></svg> <span>Comment</span></a> <i>12</i></li>
        <li><a href=""><svg class="icon left" viewBox="0 0 24 24"><path d=""></path></svg> <span>Extend</span></a>
          <ul>
            <li><a href=""><span class="icon left">icon</span> <span>Asset</span></a></li>
            <li><a href=""><span class="icon left">icon</span> <span>Page</span></a></li>
            <li><a href=""><span class="icon left">icon</span> <span>Plugin</span></a>
              <ul>
                <li>
                  <a href="">
                    <svg class="icon left" viewBox="0 0 24 24"><path d="M17.5,12A1.5,1.5 0 0,1 16,10.5A1.5,1.5 0 0,1 17.5,9A1.5,1.5 0 0,1 19,10.5A1.5,1.5 0 0,1 17.5,12M14.5,8A1.5,1.5 0 0,1 13,6.5A1.5,1.5 0 0,1 14.5,5A1.5,1.5 0 0,1 16,6.5A1.5,1.5 0 0,1 14.5,8M9.5,8A1.5,1.5 0 0,1 8,6.5A1.5,1.5 0 0,1 9.5,5A1.5,1.5 0 0,1 11,6.5A1.5,1.5 0 0,1 9.5,8M6.5,12A1.5,1.5 0 0,1 5,10.5A1.5,1.5 0 0,1 6.5,9A1.5,1.5 0 0,1 8,10.5A1.5,1.5 0 0,1 6.5,12M12,3A9,9 0 0,0 3,12A9,9 0 0,0 12,21A1.5,1.5 0 0,0 13.5,19.5C13.5,19.11 13.35,18.76 13.11,18.5C12.88,18.23 12.73,17.88 12.73,17.5A1.5,1.5 0 0,1 14.23,16H16A5,5 0 0,0 21,11C21,6.58 16.97,3 12,3Z"></path></svg> Art
                  </a>
                </li>
                <li>
                  <a href="">
                    <svg class="icon left" viewBox="0 0 24 24"><path d="M2,16V8H4L7,11L10,8H12V16H10V10.83L7,13.83L4,10.83V16H2M16,8H19V12H21.5L17.5,16.5L13.5,12H16V8Z"></path></svg> Markdown
                  </a>
                </li>
              </ul>
            </li>
            <li><a href=""><span class="icon left">icon</span> <span>Shield</span></a></li>
          </ul>
        </li>
        <li><a href=""><svg class="icon left" viewBox="0 0 24 24"><path d=""></path></svg> <span>Language</span></a></li>
        <li><a href=""><svg class="icon left" viewBox="0 0 24 24"><path d=""></path></svg> <span>Page</span></a></li>
        <li><a href=""><svg class="icon left" viewBox="0 0 24 24"><path d=""></path></svg> <span>Shield</span></a></li>
        <li><a href=""><svg class="icon left" viewBox="0 0 24 24"><path d=""></path></svg> <span>State</span></a>
          <ul>
            <li><a href=""><svg class="icon left" viewBox="0 0 24 24"><path d="M12,15.5A3.5,3.5 0 0,1 8.5,12A3.5,3.5 0 0,1 12,8.5A3.5,3.5 0 0,1 15.5,12A3.5,3.5 0 0,1 12,15.5M19.43,12.97C19.47,12.65 19.5,12.33 19.5,12C19.5,11.67 19.47,11.34 19.43,11L21.54,9.37C21.73,9.22 21.78,8.95 21.66,8.73L19.66,5.27C19.54,5.05 19.27,4.96 19.05,5.05L16.56,6.05C16.04,5.66 15.5,5.32 14.87,5.07L14.5,2.42C14.46,2.18 14.25,2 14,2H10C9.75,2 9.54,2.18 9.5,2.42L9.13,5.07C8.5,5.32 7.96,5.66 7.44,6.05L4.95,5.05C4.73,4.96 4.46,5.05 4.34,5.27L2.34,8.73C2.21,8.95 2.27,9.22 2.46,9.37L4.57,11C4.53,11.34 4.5,11.67 4.5,12C4.5,12.33 4.53,12.65 4.57,12.97L2.46,14.63C2.27,14.78 2.21,15.05 2.34,15.27L4.34,18.73C4.46,18.95 4.73,19.03 4.95,18.95L7.44,17.94C7.96,18.34 8.5,18.68 9.13,18.93L9.5,21.58C9.54,21.82 9.75,22 10,22H14C14.25,22 14.46,21.82 14.5,21.58L14.87,18.93C15.5,18.67 16.04,18.34 16.56,17.94L19.05,18.95C19.27,19.03 19.54,18.95 19.66,18.73L21.66,15.27C21.78,15.05 21.73,14.78 21.54,14.63L19.43,12.97Z"></path></svg> <span>Configuration</span></a></li>
          </ul>
        </li>
        <li><a href=""><svg class="icon left" viewBox="0 0 24 24"><path d=""></path></svg> <span>Tag</span></a></li>
        <li><a href=""><svg class="icon left" viewBox="0 0 24 24"><path d=""></path></svg> <span>User</span></a></li>
      </ul>
    </li>
    <li class="search">
      <form class="form">
        <p class="field expand">
          <span>
            <input class="input width" id="input-0-0" type="text" placeholder="Search&hellip;">
            <button class="button" type="submit">Search</button>
          </span>
        </p>
      </form>
    </li>
    <li><a href="">Page</a></li>
    <li><a href="">State</a>
      <ul>
        <li><a href="">Configuration</a></li>
      </ul>
    </li>
    <li class="right">
      <a href="">
        <img class="avatar icon only" alt="" src="jpg/200x200.jpg">
      </a>
    </li>
  </ul>
</nav>

-->