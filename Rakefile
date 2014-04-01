task :watch do
  jekyllPid = Process.spawn("jekyll serve --watch")
  sassPid = Process.spawn("sass --watch assets/scss:assets/css")
  
  trap("INT") {
    [jekyllPid, sassPid].each { |pid| Process.kill(9, pid) rescue Errno::ESRCH }
    exit 0
  }

  [jekyllPid, sassPid].each { |pid| Process.wait(pid) }
end
