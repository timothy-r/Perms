node {

   stage('Preparation') {
      // run the build script 
      sh "./build.sh"
   }
   stage('test') {
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
