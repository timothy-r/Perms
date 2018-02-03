node {
   
   stage('Install tools') {
        sh "wget https://getcomposer.org/download/1.6.3/composer.phar"
        sh "chmod +x composer.phar"
   }

   stage('Install PHP dependencies') {
        sh "cd src; ../composer.phar install"
   }

   stage('Initialise db') {
       sh "cd build; ./db-init.sh"
   }

   stage('Run tests') {
      // Run php unit tests
      if (isUnix()) {
         sh "./src/vendor/bin/phpunit -c phpunit.xml tests/unit/"
      } else {
      }
   }

   stage('Results') {
      //junit '**/target/surefire-reports/TEST-*.xml'
      //archive 'target/*.jar'
   }
}
