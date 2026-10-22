<!DOCTYPE html>
<html lang="en">

<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Mason Curlis">
    <meta name="keywords" content="job application, employment, careers, volunteering">
    <meta name="description" content="index page for Melbourne Youth Support Network job application webpage">
    <link rel="stylesheet" href="./styles/styles.css">
    <title>Melbourne Youth Support Network</title>
    <!-- GenAI acknowledgement: some parts of this page were modified with the help of GenAI. -->
    <!-- Embedded CSS for company slogan qoute-->
    <style>
        q {color:#09427e;}
    </style>
</head>

<body class="index-page">
<!-- header -->
<?php include ("header.inc"); ?>
<!-- navigation bar -->
<nav>
    <?php include ("nav.inc"); ?>
</nav>
     <!-- Company logo / banner with text on top -->
        <div id="index_banner">
                <img src="images/grp_3.png" id="company_logo" alt="Company Logo">
                <h1 id="index_logo_text_overlay">Melbourne Youth <br> Support Network</h1>
        </div>
    <main id="main-content" tabindex="-1">
        <!--    Container for company description and visual content -->
        <div id="index-description" class="content-background-and-border">
            <!-- h2, not h1: the banner above already holds the page's h1 -->
            <h2>Who We Are</h2>

            <div id="description-image-text">
                <!-- Shorter blocks and a list of roles so the description is easy to scan (Part 1 feedback) -->
                <div class="company-description">

                    <p class="description-lead">Melbourne Youth Support Network improves young lives
                        through youth outreach, volunteer organisation and fundraising. Early
                        intervention can make a huge difference to a young person's future.</p>

                    <h3>Our team</h3>
                    <ul class="team-roles">
                        <li><strong>Youth Outreach Workers</strong> build rapport with young people
                            facing vulnerability or disadvantage, offering support, advice and a
                            responsible adult to confide in.</li>
                        <li><strong>Volunteer Coordinators</strong> organise outreach events such as
                            food banks and information sessions, and train and coordinate our
                            amazing volunteers.</li>
                        <li><strong>Community Support Officers</strong> track individual cases and
                            give our outreach and volunteer teams the support each case needs.</li>
                    </ul>

                    <p>Together, we build strong connections between young people and their
                        communities.</p>

                    <!-- Link to the vacancies from the home page (Part 1 feedback) -->
                    <p class="vacancies-link"><a href="jobs.php">See our current job vacancies</a></p>

                </div>
                <div class="qoute-above-img">
                <q class="company-slogan"><b>No young person has to face life alone</b></q>
                <!-- Image source: https://creativeyouthland.org/projects/ -->

                <img src="images/indexGraphic.jpg" alt="Youth Power Graphic">
                </div>
            </div>

        </div>
        <!--    Container for program schedule table    -->
        <div id="programSchedule-layout" class="content-background-and-border">
            <h2>Program Schedule</h2>
            <p>A critical aspect of our program is to hold regular mentoring, sports
                        and recreation sessions, to provide youth free, regular events where
                        they may enjoy themselves and connect with others.</p>
            <table id="programSchedule">
                <thead>
                    <tr>
                        <th scope="col">Day</th>
                        <th scope="col">Session 1 - Morning</th>
                        <th scope="col">Session 2 - Midday</th>
                        <th scope="col">Session 3 - Afternoon</th>
                        <th scope="col">Session 4 - Evening</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">Mon</th>
                        <td><b>—</b></td>
                        <td>Mentoring</td>
                        <td colspan="2">Basketball</td>
                    </tr>
                    <tr>
                        <th scope="row">Tue</th>
                        <td>Study Support</td>
                        <td>Job Skills</td>
                        <td colspan="2">Computer Lab</td>
                    </tr>
                    <tr>
                        <th scope="row">Wed</th>
                        <td>Mentoring</td>
                        <td colspan="3">Fitness / Gym</td>
                    </tr>
                    <tr>
                        <th scope="row">Thu</th>
                        <td colspan="2">Music Making / Recording</td>
                        <td colspan="2">Visual Arts</td>
                    </tr>
                    <tr>
                        <th scope="row">Fri</th>
                        <td>Mentoring</td>
                        <td>Job Skills</td>
                        <td colspan="2">—</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!--    Container for acknowledgment of country and Australian Indigenous flag  -->
        <div id="acknowledgment-of-country" class="content-background-and-border">
            <!-- Image source https://wall.alphacoders.com/big.php?i=785129 -->
            <img src="images/Aboriginal-flag.jpg" alt="Image of Indigenous Australian Flag">
            <!-- This div ensures that the text remains in column layout, while maintaining indiviudal styling for the bold sentence at the end 
                    Example of inline styling to add padding to the text.
            -->
            <div class="acknowledgment-text" style="padding-right:10px;">
                <h2>Acknowledgement of Country</h2>
                <!-- Separate paragraphs instead of <br> line breaks, so each idea reads as its own block -->
                <p>Melbourne Youth Support Network acknowledges the Wurundjeri People of the Kulin Nation,
                    and pays respect to their Elders past, present and emerging. The Wurundjeri peoples were
                    the first practising scientists in this area, many thousands of years before colonisation.</p>
                <p>We thank them for their dedicated and ongoing care for the land that we are lucky to call home.</p>
                <p>MYSN recognises that Aboriginal and Torres Strait Islander peoples still experience significant
                    disadvantage in areas such as child protection, justice, health, housing and education. We
                    aim to include First Nations peoples in our decision making about youth support, as their knowledge
                    is invaluable in providing the right support for all young people.</p>
                <p class="no-discrimination"><b>MYSN does not tolerate racism or any other form of discrimination.</b></p>
            </div>
        </div>
    </main>

    <!-- Footer - consistent with the other pages -->
     <footer>
        <?php include ("footer.inc"); ?>
    </footer>
</body>

</html>