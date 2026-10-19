<?php
/*
filename: jobs.php
author: Mohammad Aquib
created: 13/10/2026
last modified: 14/10/2026
description: Job vacancies page. search.php gets the jobs from the database (all jobs, or
             only the ones matching the search bar) and this page shows them.

GenAI acknowledgement: the search, loop and list code used by this page was drafted with
the assistance of GenAI, then reviewed, tested and edited by Mohammad Aquib.
*/

# Gets the jobs to show: sets $search, $result and $job_count #
require_once("search.php");

# Turns text with one item per line into <li> items. Each line is escaped before it is printed #
function print_list_items($text) {
    foreach (explode("\n", $text) as $line) {
        $line = trim($line);
        if ($line !== "") {
            echo "<li>" . htmlspecialchars($line) . "</li>\n";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Mohammad Aquib">
    <meta name="keywords" content="jobs, careers, community services, youth support, volunteering, employment, Melbourne">
    <meta name="description" content="Current job vacancies at Melbourne Youth Support Network, a not-for-profit community and social services organisation.">
    <title>Job Vacancies | Melbourne Youth Support Network</title>

    <!--
        Acknowledgement of GenAI use:
        The initial page scaffold and boilerplate were drafted with the
        assistance of GenAI, then reviewed, edited and completed by
        Mohammad Aquib. All job content is fictional and written for the
        COS10026 project scenario (Group 3, G03 - Community and Social Services).
    -->

    <!-- All the main styling for this page lives in the shared
         styles.css (see its "JOBS PAGE" section) -->
    <link rel="stylesheet" href="styles/styles.css">

    <!--
        Embedded CSS example (page-specific, deliberately small):
        styles the six-character job reference number as a distinct "chip".
        Only jobs.php uses it, so it is not in the shared stylesheet.
    -->
    <style>
        .job-ref {
            display: inline-block;
            padding: 0.15em 0.5em;
            font-family: "Courier New", monospace;
            font-weight: bold;
            letter-spacing: 1px;
            color: #09427e;
            background-color: #eaf2ff;
            border: 1px solid #6495ed;
            border-radius: 4px;
        }
    </style>
</head>
<body class="jobs-page">
    <!-- header -->
    <?php include ("header.inc"); ?>
    <!-- Common navigation menu - kept identical across every page for consistency -->
    <nav>
        <?php include ("nav.inc"); ?>
    </nav>

    <!-- tabindex="-1" lets the skip link move keyboard focus here on activation -->
    <main id="main-content" tabindex="-1">
        <h1>Job Vacancies</h1>

        <div class="jobs-layout">

            <div class="job-listings">

                <!-- Intro panel: page summary, closing date note and search bar,
                     kept in one box above the job cards -->
                <div class="jobs-intro">
                    <p class="lead">
                        Melbourne Youth Support Network is growing our team of youth workers,
                        volunteer coordinators and community support staff. Explore our current
                        opportunities below &mdash; each role lists the responsibilities,
                        requirements and how to apply.
                    </p>
                    <!-- Inline CSS: a one-off emphasis on a single, time-sensitive line -->
                    <p style="color: #09427e; font-weight: bold;">
                        Each role lists its own closing date. Late applications cannot be considered.
                    </p>
                    <!-- Search bar: sends the term back to this page as ?search=... and search.php looks the jobs up in the database -->
                    <form method="get" action="jobs.php" class="job-search" role="search">
                        <label for="search">Search jobs</label>
                        <input type="search" id="search" name="search" maxlength="100"
                               placeholder="e.g. youth, volunteer, CSW214"
                               value="<?php echo htmlspecialchars($search); ?>">
                        <input type="submit" value="Search">
                    </form>
                    <?php if ($search !== ""): ?>
                        <p class="search-summary" role="status">
                            <?php echo $job_count; ?> job<?php echo $job_count == 1 ? "" : "s"; ?> found for
                            &ldquo;<?php echo htmlspecialchars($search); ?>&rdquo;.
                            <a href="jobs.php">Clear search</a>
                        </p>
                    <?php endif; ?>
                </div>

                <?php if ($job_count == 0 && $search === ""): ?>
                    <p>There are no vacancies at the moment. Please check back soon.</p>
                <?php endif; ?>

                <!-- One card per row in the jobs table -->
                <?php while ($job = mysqli_fetch_assoc($result)): ?>
                    <?php $job_id = "job-" . strtolower(htmlspecialchars($job['job_reference_number'])); ?>
                    <section aria-labelledby="<?php echo $job_id; ?>">
                        <h2 id="<?php echo $job_id; ?>"><?php echo htmlspecialchars($job['title']); ?></h2>

                        <dl class="job-meta">
                            <dt>Reference number</dt>
                            <dd><span class="job-ref"><?php echo htmlspecialchars($job['job_reference_number']); ?></span></dd>
                            <dt>Salary</dt>
                            <dd><?php echo htmlspecialchars($job['salary']); ?></dd>
                            <dt>Reports to</dt>
                            <dd><?php echo htmlspecialchars($job['reports_to']); ?></dd>
                            <dt>Applications close</dt>
                            <?php # The database stores 2026-10-25; date() turns it into "Sunday 25 October 2026" # ?>
                            <dd><time datetime="<?php echo htmlspecialchars($job['closing_date']); ?>"><?php echo date("l j F Y", strtotime($job['closing_date'])); ?></time></dd>
                        </dl>

                        <p><?php echo htmlspecialchars($job['short_description']); ?></p>

                        <h3>Key responsibilities</h3>
                        <ol>
                            <?php print_list_items($job['responsibilities']); ?>
                        </ol>

                        <h3>Essential requirements</h3>
                        <ul>
                            <?php print_list_items($job['essential_requirements']); ?>
                        </ul>

                        <h3>Preferable requirements</h3>
                        <ul>
                            <?php print_list_items($job['preferable_requirements']); ?>
                        </ul>
                    </section>
                <?php endwhile; ?>

            </div><!-- /.job-listings -->

            <!--
                Meaningful aside: content genuinely related to the vacancies but
                not part of any single job description. styles.css (JOBS PAGE
                section) positions it beside the job list - the page-specific
                CSS requirement for jobs.php.
            -->
            <aside aria-labelledby="working-here">
                <h2 id="working-here">Working at MYSN</h2>
                <p>
                    We are a not-for-profit, so our people stay because the work
                    matters. In return we offer:
                </p>
                <ul>
                    <li>Flexible and hybrid working arrangements</li>
                    <li>Salary packaging that increases your take-home pay</li>
                    <li>An annual professional-development budget</li>
                    <li>Additional paid leave over the end-of-year shutdown</li>
                    <li>Regular clinical supervision and a free employee assistance program</li>
                </ul>

                <h3>How to apply</h3>
                <ol>
                    <li>Note the six-character reference number for the role you want.</li>
                    <li>Open the <a href="apply.php">Apply</a> page and complete the form.</li>
                    <li>Enter the reference number and respond to the listed requirements.</li>
                    <li>Submit your application before the role's closing date.</li>
                </ol>

                <p class="inclusive">
                    Melbourne Youth Support Network is an inclusive employer. We
                    welcome applications from people of all backgrounds and abilities,
                    and we strongly encourage Aboriginal and Torres Strait Islander
                    people to apply.
                </p>
            </aside>

        </div><!-- /.jobs-layout -->

        <!-- Visible image credits (Part 1 feedback: credits should not only be in CSS comments) -->
        <p class="image-credits">
            Image credits: background photo by
            <a href="https://www.pexels.com/photo/15639173/">Emir Bozkurt on Pexels</a>,
            paper texture by
            <a href="https://www.pexels.com/photo/white-crumpled-paper-11605087/">Tim Mossholder on Pexels</a>
            (Pexels Licence).
        </p>
    </main>

    <!-- Footer - consistent with the other pages -->
    <footer>
        <?php include ("footer.inc"); ?>
    </footer>

</body>
</html>
