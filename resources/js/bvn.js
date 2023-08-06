    const apiEndpoint = 'https://api.youverify.co/api/v1/kyc/verifications/bvn';
    const bvnKey = 'SXFn2GA8.HwmyddDZkgmSdODrmtkHu1TwqPpagnKZ5PPE';
  
    const bvnNumber = 22507170859; // Assuming request contains the BVN number.
  
    try {
      const response = await fetch(apiEndpoint, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ' + bvnKey
        },
        body: JSON.stringify({ bvn: bvnNumber })
      }).then((res)=>{
        return res.json()
      }).then((data)=>{
        console.log(data)
      })
      .catch((e)=>{
        console.log(e)

      })
  
    }catch(e){
        console.log(e.getMessage())
    }
